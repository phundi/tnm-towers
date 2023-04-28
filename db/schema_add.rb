ActiveRecord::Schema.define(version: 0) do

  change_table(:refill) do |t|
    t.column :usage, :integer, default: 0
  end
end

