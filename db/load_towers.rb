headers = ["Sites Name", "Region", "District", "Site Status", 
            "ESCOM", "Tank Size", "Opening Litres", "Litres Dispensed", 
            "Closing Litres", "Usage in Litres", "Opening Hours", "Closing Hours",
             "Hours Run", "Average Usage Per Hour", 
            "Opening Readings", "Units Bought", "Closing Readings", "Power Usage"]
filename = "db/data/#{ARGV[0]}.csv"
creator = User.first.id
CSV.read(filename).each_with_index do |t, i|
    if i == 0 
        headers.each_with_index {|v, i| headers[i] = (v.strip.gsub(/\s+/, " ") rescue v)}
        next
    end 

    next if t[headers.index("Sites Name")].blank? 
    tower = Tower.new 
    tower.name = t[headers.index("Sites Name")].strip
    tower.district_id = Location.find_by_name(t[headers.index("District")].strip).id 
    tower.description = ARGV[1]
    tower.tower_type_id = TowerType.first.id
    tower.creator = creator

    status = t[headers.index("Site Status")]
    if status.downcase.strip == "grid"
        tower.grid_type = "On Grid Site"
    else
        tower.grid_type = "Off Grid Site"
    end 

    tower.save!

    if t[headers.index("Opening Litres")].present?
        frefill = Refill.new 
        frefill.refill_type = "FUEL"
        frefill.tower_id = tower.id 
        frefill.reading_before_refill = t[headers.index("Opening Litres")].strip
        frefill.refill_amount = t[headers.index("Litres Dispensed")].strip
        frefill.reading_after_refill = t[headers.index("Closing Litres")].strip
        frefill.usage = t[headers.index("Usage in Litres")].strip
        frefill.genset_reading = t[headers.index("Closing Hours")].strip
        frefill.genset_run_time = t[headers.index("Hours Run")].strip
        frefill.creator = creator
        frefill.refill_date = "31-May-2023".to_date
        frefill.save!
    end

    if t[headers.index("Opening Readings")].present?
        erefill = Refill.new 
        erefill.refill_type = "ESCOM"
        erefill.tower_id = tower.id 
        erefill.reading_before_refill = t[headers.index("Opening Readings")].strip
        erefill.refill_amount = t[headers.index("Units Bought")].strip

        if t[headers.index("Closing Readings")].strip.present? 
            erefill.reading_after_refill = t[headers.index("Closing Readings")].strip
        else 
            c = t[headers.index("Opening Readings")].strip.to_i + t[headers.index("Units Bought")].strip.to_i
            erefill.reading_after_refill = c
        end 

        erefill.usage = t[headers.index("Power Usage")].strip
        erefill.creator = creator
        erefill.refill_date = "31-May-2023".to_date
        erefill.save!
    end 




    puts "#{i} # #{tower.name}"
end

