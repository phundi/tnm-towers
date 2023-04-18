ActiveRecord::Schema.define(version: 0) do

  create_table "refill", primary_key: "refill_id", force: :cascade do |t|
    t.string   "refill_type"
    t.integer  "tower_id",  null: false
    t.integer  "reading_before_refill",  null: false
    t.integer  "refill_amount",  null: false
    t.integer  "reading_after_refill",  null: false
    t.date     "refill_date",    null: false
    t.integer  "creator",  null: false
    t.datetime "created_at",                             null: false
    t.datetime "updated_at",                             null: false
  end
end

