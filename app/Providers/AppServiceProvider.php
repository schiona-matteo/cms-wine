<?php

namespace App\Providers;

use Cmixin\BusinessDay;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Carbon\Carbon::setLocale('it');

        Collection::macro('sortByDate', function ($column = 'created_at', $order = SORT_DESC) {
            /* @var $this Collection */
            // @phpstan-ignore-next-line
            return $this->sortBy(function ($comment) use ($column) {
                return strtotime($comment->$column);
            }, SORT_REGULAR, $order == SORT_DESC);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Database\Query\Builder::macro('toRawSql', function () {
            // @phpstan-ignore-next-line
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
            }, $this->toSql()); // @phpstan-ignore-line
        });

        \Illuminate\Database\Eloquent\Builder::macro('toRawSql', function () {
            return $this->getQuery()->toRawSql(); // @phpstan-ignore-line
        });

        \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');

        BusinessDay::enable('Illuminate\Support\Carbon', 'it-national');
    }
}
