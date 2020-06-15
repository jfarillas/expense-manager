<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Expense extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expense_date',
        'categories_id',
        'account',
        'amount',
        'description'
    ];

    /**
     * Get the category for the specific expense.
     */
    public function categories()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get expenses per category.
     */
    public static function expensesPerCategory()
    {
        return DB::table(function ($query) {
            $query->selectRaw('b.type, IFNULL(SUM(a.amount), 0) total_amount')
            ->from('expenses AS a')
            ->rightJoin('categories AS b', 'a.categories_id', '=', 'b.id')
            ->groupBy('b.type');
        }, 'grp_expenses');
    }

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
