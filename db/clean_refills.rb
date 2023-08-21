refills = Refill.find_by_sql(
    "
        SELECT r.* FROM refill r
            INNER JOIN tower t ON r.tower_id = t.tower_id
        WHERE DATE(r.created_at) = '2023-08-21' AND t.description = 'blantyre'
    "
)

puts "DELETING #{refills.count} REFILLS FOR Blantyre"

refills.each do |r|
    r.destroy
end 