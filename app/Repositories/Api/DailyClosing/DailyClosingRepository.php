<?php

namespace App\Repositories\Api\DailyClosing;

use App\Models\DailyClosing;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class DailyClosingRepository
{
    /**
     * Get all daily closings with pagination
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return DailyClosing::with('closedByUser')
            ->orderBy('closing_date', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find daily closing by date
     */
    public function findByDate(string $date): ?DailyClosing
    {
        return DailyClosing::with('closedByUser')
            ->where('closing_date', $date)
            ->first();
    }

    /**
     * Get today's closing if exists
     */
    public function getTodayClosing(): ?DailyClosing
    {
        return $this->findByDate(Carbon::today()->toDateString());
    }

    /**
     * Create new daily closing
     */
    public function create(array $data): DailyClosing
    {
        return DailyClosing::create($data);
    }

    /**
     * Find by ID
     */
    public function find(int $id): ?DailyClosing
    {
        return DailyClosing::with('closedByUser')->find($id);
    }
}
