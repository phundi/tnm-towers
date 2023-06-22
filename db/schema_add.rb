ActiveRecord::Schema.define(version: 0) do

  change_table(:tower) do |t|
    t.column :code, :string
  end
end

