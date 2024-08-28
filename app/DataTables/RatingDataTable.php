<?php

namespace App\DataTables;

use App\Models\Rating;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RatingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable ( QueryBuilder $query ) : EloquentDataTable
    {
        return ( new EloquentDataTable( $query ) )
            ->addColumn ( 'serial_number', function () {
                static $count = 0;
                return ++$count;
            } )
            // ->addColumn ( 'professional', function ($query) {
            //     return $query->Professional->fullname ?? '';
            // } )
            ->addColumn ( 'booking_id', function ($query) {
                return $query->booking_id;
            } )
            ->addColumn ( 'client_rating_points', function ($query) {
                $points = $query->client_rating_points;
                return $this->calculateRating ( $points );
            } )
            ->addColumn ( 'client_comment', function ($query) {
                return $query->client_comment;
            } )
            ->addColumn ( 'professional_rating_points', function ($query) {
                $points = $query->professional_rating_points;
                return $this->calculateRating ( $points );
            } )
            ->addColumn ( 'professional_comment', function ($query) {
                return $query->professional_comment;
            } )
            ->addColumn ( 'created_at', function ($query) {
                return $query->created_at->format ( 'Y-m-d H:i' );
            } )
            ->setRowId ( 'id' );
    }

    /**
     * Get the query source of dataTable.
     */
    public function query ( Rating $model ) : QueryBuilder
    {
        return $model->newQuery ();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html () : HtmlBuilder
    {
        return $this->builder ()
            ->setTableId ( 'ratings-table' )
            ->columns ( $this->getColumns () )
            // ->minifiedAjax ()
            ->dom ( 'Bfrtip' )
            ->orderBy ( 0 )
            ->selectStyleSingle ()
            ->buttons ( [
                Button::make ( 'excel' ),
                Button::make ( 'csv' ),
                // Button::make ( 'pdf' ),
                Button::make ( 'print' ),
                Button::make ( 'reset' ),
                Button::make ( 'reload' ),
            ] );
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns () : array
    {
        return [
            Column::make ( 'serial_number' )->title ( trans ( 'admin_fields.serial_number' ) ),
            Column::make ( 'booking_id' )->title ( trans ( 'admin_fields.booking_no' ) ),
            Column::make ( 'client_rating_points' )->title ( trans ( 'admin_fields.client_rating_points' ) ),
            Column::make ( 'client_comment' )->title ( trans ( 'admin_fields.client_comment' ) ),
            Column::make ( 'professional_rating_points' )->title ( trans ( 'admin_fields.professional_rating_points' ) ),
            Column::make ( 'professional_comment' )->title ( trans ( 'admin_fields.professional_comment' ) ),
            Column::make ( 'created_at' )->title ( trans ( 'admin_fields.date_time' ) ),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename () : string
    {
        return 'Rating_' . date ( 'YmdHis' );
    }

    // protected function calculateRating ( $points )
    // {
    //     // Calculate whole stars
    //     $wholeStars = floor ( $points );

    //     // Calculate remaining fractional points
    //     $remainingPoints = $points - $wholeStars;

    //     // Initialize the rating string with whole stars
    //     $rating = str_repeat ( '⭐', $wholeStars );

    //     // Add fractional star if remaining points are greater than 0
    //     if ( $remainingPoints > 0 ) {
    //         // Determine the fractional part of the star based on remaining points
    //         if ( $remainingPoints <= 0.3 ) {
    //             $rating .= '⭐'; // Add a quarter of a star
    //         } elseif ( $remainingPoints <= 0.6 ) {
    //             $rating .= '⭐⭐'; // Add half of a star
    //         } else {
    //             $rating .= '⭐⭐⭐'; // Add three quarters of a star
    //         }
    //     }

    //     return $rating;
    // }


    // protected function calculateRating ( $points )
    // {
    //     // Calculate whole stars
    //     $wholeStars = floor ( $points );

    //     // Calculate remaining fractional points
    //     $fractionalPart = $points - $wholeStars;

    //     // Initialize the rating string with whole stars
    //     $rating = str_repeat ( '⭐', $wholeStars );

    //     // Add fractional star if remaining points are greater than 0
    //     if ( $fractionalPart > 0 ) {
    //         // Determine the number of stars to add based on the fractional part
    //         $fractionalStars = round ( $fractionalPart * 10 ) / 2; // Convert fractional part to half-stars
    //         $rating .= str_repeat ( '⭐', $fractionalStars );
    //     }

    //     return $rating;
    // }

    protected function calculateRating ( $points )
    {
        // Calculate whole stars
        $wholeStars = floor ( $points );

        // Calculate remaining fractional points
        $remainingPoints = $points - $wholeStars;

        // Initialize the rating string with whole stars
        $rating = str_repeat ( '⭐', $wholeStars );

        // Add fractional star if remaining points are greater than 0
        if ( $remainingPoints > 0 ) {
            // Determine the fractional part of the star based on remaining points
            if ( $remainingPoints < 0.25 ) {
                $rating .= ''; // No additional star
            } elseif ( $remainingPoints < 0.75 ) {
                $rating .= '⭐'; // Add half star
            } else {
                $rating .= '⭐'; // Add one whole star
            }
        }

        return $rating;
    }


}
