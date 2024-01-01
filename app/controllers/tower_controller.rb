class TowerController < ApplicationController

  def new_refill 
    @tower = Tower.find(params[:tower_id])
    @prev_refill = Refill.where(" tower_id = #{@tower.id} AND refill_type = '#{params[:type]}'  ")
                    .order(" refill_date ASC, created_at ASC ").last
    @refill = Refill.new 
    @unit = params[:type].upcase == "ESCOM" ? "Units" : "Litres"

    if request.post?

      @refill.refill_date = params[:refill_date] #Time.now 
      @refill.reading_before_refill  = params[:reading_before_refill]
      @refill.reading_after_refill  = params[:refill_final_reading]
      @refill.refill_amount  = params[:refill_amount]
      @refill.usage = params[:refill_usage]
      @refill.genset_reading = params[:genset_reading]
      @refill.hours_before = (params[:genset_reading].to_i - params[:refill_run_hours].to_i)
      @refill.genset_run_time = params[:refill_run_hours]
      @refill.refill_type  = params[:type].upcase
      @refill.tower_id  = @tower.id 
      @refill.creator = @cur_user.id
      @refill.save!

      redirect_to "/tower/view?tower_id=#{@tower.id}"
    end 

  end 

  def tower_types
    @tower_types = TowerType.where(voided: 0).order('name')
  end

  def index
    @types = TowerType.where(voided: 0)
    @label = Date.today.strftime("%d %b, %Y")
    @periods = ["May, 2023", "June, 2023", "July, 2023", "August, 2023", 
                  "September, 2023", "October, 2023", "November, 2023", "December, 2023", "January, 2024"]
  end

  def new
    @tower = Tower.new
    @action = "/tower/new"
    @types = TowerType.where(voided: 0)

    district_tag = LocationTag.where(name: "District").first 
    @districts = Location.find_by_sql("select * from location l INNER JOIN location_tag_map tm ON tm.location_id = l.location_id 
      WHERE tm.location_tag_id = #{district_tag.id}")
    if request.post?
      @tower = Tower.new
      @tower.tower_type_id = params[:type]
      @tower.name = params[:name]
      @tower.code = params[:code]
      @tower.district_id = params[:district]
      @tower.lat = params[:lat]
      @tower.long = params[:long]
      @tower.description = params[:description].strip
      @tower.creator = @cur_user.id
      @tower.save
      redirect_to "/tower/view?tower_id=#{@tower.id}"
    end
  end

  def edit
    @tower = Tower.find(params[:tower_id])

    district_tag = LocationTag.where(name: "District").first 
    @districts = Location.find_by_sql("select * from location l INNER JOIN location_tag_map tm ON tm.location_id = l.location_id 
      WHERE tm.location_tag_id = #{district_tag.id}")

    @action = "/tower/edit"
    @types = TowerType.where(voided: 0)

    if request.post?
      @tower.tower_type_id = params[:type]
      @tower.name = params[:name]
      @tower.code = params[:code]
      @tower.district_id = params[:district]
      @tower.lat = params[:lat]
      @tower.long = params[:long]
      @tower.description = params[:description].strip
      @tower.creator = @cur_user.id
      @tower.save
      redirect_to "/tower/view?tower_id=#{@tower.id}"
    end
  end

  def view

    @tower = Tower.find(params[:tower_id])
    @tower_type = TowerType.find(@tower.tower_type_id).name
    @district_name = Location.find(@tower.district_id).name
    @creator = User.find(@tower.creator).name

    escom_refills_count = Refill.where(" tower_id = #{@tower.id} AND refill_type = 'ESCOM'  ").count
    fuel_refills_count = Refill.where(" tower_id = #{@tower.id} AND refill_type = 'FUEL'  ").count

    @modules = []
    @modules <<  ['ESCOM Units Refills', escom_refills_count, "/tower/refills?type=escom&tower_id=#{@tower.id}" ]
    @modules <<  ['Fuel Refills', fuel_refills_count, "/tower/refills?type=fuel&tower_id=#{@tower.id}"]

    @common_encounters = []
    @common_encounters << ['New Escom Units Refill', '/tower/new_refill?type=ESCOM']
    @common_encounters << ['New Fuel Refill', '/tower/new_refill?type=FUEL']
  end

  def new_type
    @tower_type = TowerType.new
    @action = "/tower/new_type"
    if request.post?
      TowerType.create(name: params[:name], description: params[:description], voided: 0)
      redirect_to "/tower/tower_types" and return
    end
  end

  def view_type
    @tower_type = TowerType.find(params[:type_id])
  end

  def edit_type
    @action = "/tower/edit_type"
    @tower_type = TowerType.find(params[:type_id])
    if request.post?
      @tower_type.name = params[:name]
      @tower_type.description = params[:description]
      @tower_type.save
      redirect_to "/tower/tower_types" and return
    end
  end

  def delete_type
    type = TowerType.find(params[:type_id])
    type.voided = 1
    type.save
    redirect_to '/tower/tower_types'
  end

  def ajax_towers

    if params[:period].present?
      d = "10 #{params[:period]}".to_date 
      start_date = d.beginning_of_month.to_s(:db)
      end_date = d.end_of_month.to_s(:db)
    else
      start_date = Date.today.beginning_of_month.to_s(:db)
      end_date = Date.today.end_of_month.to_s(:db)
    end 
    mtd_date_filter = " AND refill_date BETWEEN '#{start_date}' AND '#{end_date}' "

    search_val = params[:search][:value] rescue nil
    search_val = '' if search_val.blank?

    tag_filter = ''
    flagged_filter = ''
    having_filter = ''
    search_filter = ''
    region_filter = ''

    if params[:type_id].present?
      tag_filter = " AND tower.tower_type_id = #{params[:type_id]}"
    end

    if params[:region].present?
      region_filter = " AND tower.description = '#{params[:region]}' "
    end

    if params[:flagged].present? and params[:flagged].to_s == "true"

      having_filter = " AND (usage_mtd/run_hours_mtd) > 3 "
      
    end

    if search_val.present?
      search_filter = " AND tower.name REGEXP '#{search_val}' "
    end 

    data = Tower.order(' tower.created_at DESC ')
    data = data.where(" true #{search_filter}
         #{tag_filter}  #{region_filter} ")
    total = data.select(" count(*) c ")[0]['c'] rescue 0
    page = (params[:start].to_i / params[:length].to_i) + 1


    data = data.select(" tower.* , 
    
            (SELECT SUM(refill.usage) FROM refill 
                    WHERE refill.tower_id = tower.tower_id AND refill_type = 'FUEL' #{mtd_date_filter}) AS usage_mtd,

            (SELECT SUM(refill.genset_run_time) FROM refill 
                    WHERE refill.tower_id = tower.tower_id AND refill_type = 'FUEL' #{mtd_date_filter}) AS run_hours_mtd

     ").having(" true #{having_filter} ")

    data = data.page(page).per_page(params[:length].to_i)
    rescue_value = "0"

    @records = []
    data.each do |p|
      type = TowerType.find(p.tower_type_id).name rescue nil
      
      escom_refill = Refill.where(" tower_id = #{p.id} AND refill_type = 'ESCOM'  #{mtd_date_filter} ")
      .order(" refill_date").last

      fuel_refill = Refill.where(" tower_id = #{p.id} AND refill_type = 'FUEL'  #{mtd_date_filter}")
      .order(" refill_date ").last


      fuel_refill_last_month = Refill.where(" tower_id = #{p.id} AND refill_type = 'FUEL'  
        AND refill_date < '#{start_date}'
      ").order(" refill_date ").last 

      escom_refill_last_month = Refill.where(" tower_id = #{p.id} AND refill_type = 'ESCOM'  
        AND refill_date < '#{start_date}'
      ").order(" refill_date ").last  

      escom_refills_mtd = Refill.find_by_sql(" SELECT SUM(refill_amount) AS total FROM refill 
                    WHERE tower_id = #{p.id} AND refill_type = 'ESCOM' #{mtd_date_filter}
                     " ).last.total rescue 0

      fuel_refills_mtd = Refill.find_by_sql(" SELECT SUM(refill_amount) AS total FROM refill 
                    WHERE tower_id = #{p.id} AND refill_type = 'FUEL' #{mtd_date_filter} " ).last.total rescue 0

      escom_usage_mtd = Refill.find_by_sql(" SELECT SUM(refill.usage) AS total FROM refill 
                    WHERE tower_id = #{p.id} AND refill_type = 'ESCOM' #{mtd_date_filter} " ).last.total rescue 0

     # if p.run_hours_mtd.to_f > 0
       rate = (p.usage_mtd.to_f/p.run_hours_mtd.to_f).round(2)
      #else
      #  rate = 0
      #end 


      rdate = [(fuel_refill.refill_date rescue nil), (escom_refill.refill_date rescue nil)].delete_if{|s| 
                s.blank?}.max.strftime("%Y-%m-%d") rescue ""
      
      rate = "?" if rate.to_s == "NaN" || rate.to_s == "Infinity"

      if rate == "?" || rate > 3
          rate = "<span style='color:red'>#{rate}</span>"
      end 
      gen_last_month = h(fuel_refill.genset_reading - fuel_refill.genset_run_time) rescue rescue_value

      row = [rdate,
                p.name, 
                p.code,
                (fuel_refill_last_month.reading_after_refill rescue 
                    (fuel_refill.reading_before_refill rescue rescue_value)),
                (fuel_refills_mtd || 0),
                (fuel_refill.reading_after_refill rescue rescue_value),
                (p.usage_mtd || 0),
                (fuel_refill_last_month.genset_reading rescue (fuel_refill.hours_before rescue 0)),
                (fuel_refill.genset_reading rescue rescue_value),
                (p.run_hours_mtd || 0),
                rate,
                (escom_refill_last_month.reading_after_refill rescue 
                  (escom_refill.reading_before_refill rescue rescue_value)),
                (escom_refills_mtd || 0),
                (escom_refill.reading_after_refill rescue rescue_value),
                (escom_usage_mtd || 0),
                p.id]
      @records << row
    end

    render :text => {
        "draw" => params[:draw].to_i,
        "recordsTotal" => total,
        "recordsFiltered" => total,
        "data" => @records}.to_json and return
  end

  def refills

    @periods = ["May, 2023", "June, 2023", "July, 2023", "August, 2023", "September, 2023", 
                    "October, 2023", "November, 2023", "December, 2023", "January, 2024"] 
    region_filter = " "

    if params[:region].present?
      region_filter = " AND t.description = '#{params[:region]}' "
    end 

    if params[:period].present? 
      d = "10 #{params[:period]}".to_date 
      start_date = d.beginning_of_month.to_s(:db)
      end_date = d.end_of_month.to_s(:db)
    else
      start_date, end_date = ["1900-01-01".to_date.to_s, Date.today.to_s]
    end 

    tower_id = params[:tower_id]
    tower_filter = " "; tower_name = ""
    if tower_id.present?
      tower_name = " for " + Tower.find(tower_id).name 
      tower_filter = " AND t.tower_id = #{tower_id}"
    end 

    type_filter = " "

    @title = "Listing of #{params[:type]} Refills #{tower_name}"

    @data = [
                ["Refill date", "Tower", "Code", "Region", "District", "Technician", "Refill type", 
              "Reading before refill", "Usage"]]

    if params[:type] != 'escom'
      @data[0] << "Run hrs"
      @data[0] << "Rate (Litres/hr)"

    end

    @data[0] = @data[0] + ["Refill amount", "Final reading"]
    
    if params[:type] == "escom"
      type_filter = " AND r.refill_type = 'ESCOM' "
    elsif params[:type] == "fuel"
      type_filter = " AND r.refill_type = 'FUEL' "
    end 

    data = Tower.find_by_sql("
          SELECT r.*, l.code , t.code AS code2, t.description AS region, t.name FROM refill r  
            INNER JOIN tower t ON t.tower_id = r.tower_id
            INNER JOIN location l ON l.location_id = t.district_id
            WHERE DATE(r.refill_date) BETWEEN '#{start_date}' AND '#{end_date}' #{region_filter}
            #{tower_filter} #{type_filter} ORDER BY refill_date DESC
    ").each do |t|
        
        creator = User.find(t.creator).name   
        
        rate = ""
        if t.usage.present? and t.usage > 0 and t.genset_run_time.present? and t.genset_run_time > 0
          rate = (t.usage/t.genset_run_time.to_f).round(2)
          if rate > 3
            rate = "<span style='color:red'>#{rate}</span>".html_safe
          end 
        end 
        
        row = [   
          t.refill_date.strftime("%Y-%m-%d"),
          t.name, 
          t.code2,
          t.region,
                t.code, 
                creator,
                t.refill_type,
                t.reading_before_refill,
                t.usage
              ]

              if params[:type] != 'escom'
                row << t.genset_run_time
                row << rate
              end 

              row += [
                t.refill_amount,
                t.reading_after_refill,
                t.id
          ]
      @data << row
    end


    render template: "tower/generic_table"  
end



  def date_ranges 
      
    start_date, end_date = ["1900-01-01".to_date.to_s, Date.today.to_s]
    start_date = params[:start_date].to_date.to_s if params[:start_date].present?
    end_date = params[:end_date].to_date.to_s if params[:end_date].present?

    if params['period'].present?
      start_date, end_date = {
        "today" => [Date.today, Date.today],
        "week"  => [Time.now.beginning_of_week.to_date, Time.now.end_of_week.to_date],
        "month"  => [Time.now.beginning_of_month.to_date, Time.now.end_of_month.to_date],
        "year"  => [Time.now.beginning_of_year.to_date, Time.now.end_of_year.to_date],
        "eversince"  => ["1900-01-01".to_date.to_s, Date.today.to_s]
      }[params['period']]
    end 

    [start_date, end_date]
  end

  def summary_report 

    if request.post?

      start_date, end_date = date_ranges
      ongrid = Tower.where(" created_at <= '#{end_date}'  AND grid_type = 'On Grid Site' ").count
      offgrid = Tower.where(" created_at <= '#{end_date}'  AND grid_type = 'Off Grid Site' ").count

      bt_ids = Location.where(" name IN ('Blantyre', 'Blantyre City') ").pluck :location_id
      ll_ids = Location.where(" name IN ('Lilongwe', 'Lilongwe City') ").pluck :location_id
      south_ids = Location.where(" description = 'SOUTH'  AND location_id NOT IN (#{bt_ids.join(',')}) ").pluck :location_id
      centre_ids = Location.where(" description = 'CENTRE'  AND location_id NOT IN (#{ll_ids.join(',')}) ").pluck :location_id
      north_ids = Location.where(" description = 'NORTH'  AND location_id NOT IN (#{bt_ids.join(',')}) ").pluck :location_id



      bt_usage = Refill.find_by_sql(" SELECT SUM(r.usage) AS total FROM refill r
        INNER JOIN tower t ON t.tower_id = r.tower_id  AND r.refill_type = 'FUEL'
        WHERE t.description = 'Blantyre' AND r.refill_date BETWEEN '#{start_date}' AND '#{end_date}' 
      ").last.total || 0
     
      ll_usage = Refill.find_by_sql(" SELECT SUM(r.usage) AS total FROM refill r
        INNER JOIN tower t ON t.tower_id = r.tower_id 
        WHERE t.description = 'Lilongwe' AND r.refill_type = 'FUEL'
          AND r.refill_date BETWEEN '#{start_date}' AND '#{end_date}' 
      ").last.total || 0

      south_usage = Refill.find_by_sql(" SELECT SUM(r.usage) AS total FROM refill r
      INNER JOIN tower t ON t.tower_id = r.tower_id 
      WHERE t.description = 'South' AND r.refill_type = 'FUEL'
        AND r.refill_date BETWEEN '#{start_date}' AND '#{end_date}' 
      ").last.total || 0
          

      north_usage = Refill.find_by_sql(" SELECT SUM(r.usage) AS total FROM refill r
      INNER JOIN tower t ON t.tower_id = r.tower_id 
      WHERE t.description = 'North' AND r.refill_type = 'FUEL'
        AND r.refill_date BETWEEN '#{start_date}' AND '#{end_date}' 
      ").last.total || 0

      all_usage = Refill.find_by_sql(" SELECT SUM(r.usage) AS total FROM refill r
      WHERE r.refill_type = 'FUEL'
        AND r.refill_date BETWEEN '#{start_date}' AND '#{end_date}' 
      ").last.total || 0

      variance_usage = params[:budgeted_usage].to_i - all_usage
      if variance_usage < 0
        variance_usage = "<span style='color:red'>#{variance_usage}</span>"
      end 

      actual_dg_running_hours = Refill.find_by_sql(" SELECT SUM(r.genset_run_time) AS total FROM refill r
      WHERE r.refill_type = 'FUEL'
        AND r.refill_date BETWEEN '#{start_date}' AND '#{end_date}' 
      ").last.total || 0

      variance_run_hours = params[:budgeted_dg_running_hours].to_i  - actual_dg_running_hours 
      if variance_run_hours < 0
        variance_run_hours = "<span style='color:red'>#{variance_run_hours}</span>"
      end 

      rate = (all_usage.to_f/actual_dg_running_hours.to_f).round(2)
      
      rate_variance = (params[:budgeted_dg_consumption_rate].to_f - rate ).round(2)
      if rate_variance < 0
        rate_variance = "<span style='color:red'>#{rate_variance}</span>"
      end 

      if rate > 3
        rate = "<span style='color:red'>#{rate}</span>"
      end 

      @data = [
                          ["", "Unit of Measure", 'Description', params[:end_date].to_date.strftime("%d-%b-%Y")],

                          ["Number of sites", "Count", 'Off Grid Sites', offgrid],
                              ["", "", 'On Grid Sites', ongrid],
                              ["", "", 'Total No. of Sites', (ongrid + offgrid)],
                              ['', '', "", ""],
                              ["", "", "<b>Usage (Litres)</b>", ""],
                              ["", "", "Blantyre", bt_usage],
                              ["", "", "Lilongwe", ll_usage],
                              ["", "", "North", north_usage],
                              ["", "", "South", south_usage],
                              ["", "", "", ""],

                          ["DG Fuel Usage",  "Litres", '<b>Actual Usage</b>', "<b>#{all_usage}</b>"],
                              ["", "", '<b>Budgeted Usage</b>', "<b>#{params[:budgeted_usage]}</b>"],
                              ["", "", '<b>Variance</b>', "<b>#{variance_usage}</b>"],
                              ["", "", "", ""],

                          ["DG Running Hours", "Hours", 'Actual DG Running Hours', actual_dg_running_hours],
                              ["", "", 'Budgeted DG Running Hours', params[:budgeted_dg_running_hours]],
                              ["", "", 'Variance Running Hours', variance_run_hours],
                              ["", "", "", ""],

                          ["DG Fuel Consumption Rate", "Litres/Hour", 
                                          'Actual Consumption Rate', rate],
                              ["", "", 'Budgeted Consumption Rate', 
                                            params[:budgeted_dg_consumption_rate]],
                              ["", "", 'Variance Consumption Rate', rate_variance],
                              ["", "", "", ""],
                      ]

            render :template => "tower/generic_report_table" and return

    end 
  end 

  def backup 

    require 'yaml'
    configs = YAML.load_file("#{Rails.root}/config/database.yml")[Rails.env]
    file_name = "backups/#{Time.now.strftime('%Y_%b_%d_backup_%H_%M.sql')}"
    
    `rm backups/*.sql.gz`

    `mysqldump -u#{configs['username']} -p#{configs['password']} #{configs['database']} > #{file_name}`
    `gzip #{file_name}`

    send_file ("#{file_name}.gz")
  end 

end 
