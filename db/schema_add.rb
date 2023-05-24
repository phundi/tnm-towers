ActiveRecord::Schema.define(version: 0) do

  change_table(:tower) do |t|
    t.column :grid_type, :string, default: "On Grid Site"
  end
end

