
filename = "db/data/#{ARGV[0]}.csv"
CSV.read(filename).each_with_index do |t, i|
   
    next if i == 0

    code = t[1].strip rescue next
    name = t[0].strip

    next if name.blank? 

    towers = Tower.where(name: name)

    tower = towers.first
raise name.inspect if tower.blank?
    next if tower.blank? 

    tower.code = code 
    tower.save!

    puts "#{i} # #{tower.name} # #{code}" 
end

