<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DeliveryPerson extends Model
{
    use Notifiable;
    
    protected $table='delivery_people';

    protected $fillable=['user_id','join_date','tracking_id'];

     /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

      /**
     * Creates a new user based on the input provided.
     *
     * @return object
     */
    public static function getAllDeliveryPeople()
    {
        $deliveryPeople = DeliveryPerson::leftJoin('users as u','delivery_people.user_id','=','u.id')
                        ->select(
                            DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as delivery_person"),
                            DB::raw("CONCAT(COALESCE(latitude, ''),' ,',COALESCE(longitude, '')) as location")

                        )
                        ->get();
        $deliveryPeople=Arr::pluck($deliveryPeople,'location','delivery_person');
        return $deliveryPeople;
    }

    public static function forDropdown()
    {
        $deliveryPeople = DeliveryPerson::leftJoin('users','delivery_people.user_id','=','users.id')
             ->select('delivery_people.id', DB::raw("CONCAT(COALESCE(users.surname, ''),' ',COALESCE(users.first_name, ''),' ',COALESCE(users.last_name,'')) as text"));
        
        $deliveryPeople = $deliveryPeople->pluck('text', 'id');

        $deliveryPeople = $deliveryPeople->prepend(__('lang_v1.none'), '');

        return $deliveryPeople;
    }
}
