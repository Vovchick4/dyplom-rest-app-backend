<?php

namespace App\Http\Controllers\Api\Admin;

use \stdClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\StatisticsRestaurant;
use App\Http\Controllers\Controller;

class StatisticsRestaurantController extends Controller
{
    private $revies_types = array("excellent", "good", "satisfactory", "poor", "bad");

    /**
     * Show restaurant stats.
     * @param Request $request
     * @param StatisticsRestaurant $StatisticsRestaurant
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // get user restaurant_id
        $restaurantId = $user->restaurant_id;

        if (
            $user->role == self::SUPER_ADMIN
            && !empty($request->header('restaurant'))
        ) {
            $restaurantId = $request->header('restaurant');
        }

        $currentTime = Carbon::now();
        $statisticsRestaurant = StatisticsRestaurant::where('restaurant_id', $restaurantId);
        $data = new stdClass();
        $totalReview = $statisticsRestaurant->count();

        // if ($totalReview == 0) {
        //     return response()->json(['status' => 404, 'message' => 'Havent reviews for this rest'], 404);
        // }

        $rw = $this->revies_types;
        foreach ($rw as &$rt) {
            $currCategory = $statisticsRestaurant->where('review', $rt);
            $currCategoryCount = $currCategory->count();
            $currCategoryCountForLastMonth = $currCategory->whereBetween('created_at', [Carbon::now()->subDays(30)->toISOString(), $currentTime->toISOString()])->count();
            if (!isset($data->{$rt}))
                $data->{$rt} = new stdClass('count', 'percentage', 'percentageAlt', 'up');
            $data->{$rt}->count = $currCategoryCount;
            $percCurrCategory = $this->countCategoriesCount($currCategoryCount, $totalReview);
            $percCurrCategoryLstMnth = $this->countCategoriesCount($currCategoryCountForLastMonth, $totalReview);
            $data->{$rt}->percentage = $percCurrCategory;
            if ($percCurrCategoryLstMnth == 0) {
                $data->{$rt}->percentageAlt = 0;
            } else {
                $data->{$rt}->percentageAlt = (int) $percCurrCategory - $percCurrCategoryLstMnth;
            }
            $data->{$rt}->up = $percCurrCategoryLstMnth <= $percCurrCategory;
        }
        return response()->json(['data' => $data, 'status' => 200, 'message' => 'message.ok'], 200);
    }

    private function countCategoriesCount(int $count, int $totalRew)
    {
        if ($count == 0) {
            return 0;
        }
        return ($count / $totalRew) * 100;
    }
}
