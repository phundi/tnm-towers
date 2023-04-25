class TowerController < ApplicationController

  def escom_refill 
    @tower = Tower.find(params[:tower_id])
    @prev_refill = Refill.where(" tower_id = #{@tower.id} AND refill_type = 'ESCOM'  ")
                    .order(" refill_date ASC, created_at ASC ").last
    @refill = Refill.new 

    if request.post?

      @refill.refill_date = Time.now #params[:refill_date]
      @refill.reading_before_refill  = params[:reading_before_refill]
      @refill.reading_after_refill  = params[:reading_after_refill]
      @refill.refill_amount  = params[:refill_amount]
      @refill.refill_type  = "ESCOM"
      @refill.tower_id  = @tower.id 
      @refill.creator = @cur_user.id
      @refill.save!

      redirect_to "/tower/view?tower_id=#{@tower.id}"
    end 

  end 

  def fuel_refill 
    @tower = Tower.find(params[:tower_id])
    @prev_refill = Refill.where(" tower_id = #{@tower.id} AND refill_type = 'FUEL'  ")
                    .order(" refill_date ASC, created_at ASC ").last
    @refill = Refill.new 

    if request.post?

      @refill.refill_date = Time.now #params[:refill_date]
      @refill.reading_before_refill  = params[:reading_before_refill]
      @refill.reading_after_refill  = params[:reading_after_refill]
      @refill.refill_amount  = params[:refill_amount]
      @refill.refill_type  = "FUEL"
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
    @modules <<  ['ESCOM Units Refills', escom_refills_count, "/tower/refills?tower_id=#{@tower.id}" ]
    @modules <<  ['Fuel Refills', fuel_refills_count, "/tower/refills?tower_id=#{@tower.id}"]

    @common_encounters = []
    @common_encounters << ['New Escom Units Refill', '/tower/escom_refill']
    @common_encounters << ['New Fuel Refill', '/tower/fuel_refill']
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

    search_val = params[:search][:value] rescue nil
    search_val = '_' if search_val.blank?

    tag_filter = ''
    code_filter = ''

    if params[:type_id].present?
      tag_filter = " AND tower.tower_type_id = #{params[:type_id]}"
    end

    data = Tower.order(' tower.created_at DESC ')
    data = data.where(" ((CONCAT_WS(name, description, '_') REGEXP '#{search_val}')
         #{tag_filter}) #{code_filter}")
    total = data.select(" count(*) c ")[0]['c'] rescue 0
    page = (params[:start].to_i / params[:length].to_i) + 1

    data = data.select(" tower.* ")
    data = data.page(page).per_page(params[:length].to_i)

    @records = []
    data.each do |p|
      type = TowerType.find(p.tower_type_id).name rescue nil
      district_name = Location.find(p.district_id).code
      
      escom_refill = Refill.where(" tower_id = #{p.id} AND refill_type = 'ESCOM'  ").last

      fuel_refill = Refill.where(" tower_id = #{p.id} AND refill_type = 'FUEL'  ").last

      row = [p.name, 
                district_name, 
                (escom_refill.refill_date.strftime("%d-%b-%Y") rescue ""),
                (escom_refill.refill_amount rescue ""), 
                (escom_refill.reading_after_refill rescue ""), 
                (fuel_refill.refill_date.strftime("%d-%b-%Y") rescue ""), 
                (fuel_refill.refill_amount rescue ""), 
                (fuel_refill.reading_after_refill rescue ""),
            p.id]
      @records << row
    end

    render :text => {
        "draw" => params[:draw].to_i,
        "recordsTotal" => total,
        "recordsFiltered" => total,
        "data" => @records}.to_json and return
  end

end
