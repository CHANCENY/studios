<?php

namespace Modules\Renders;

use Datainterface\Query;
use Datainterface\Selection;

/**
 *
 */
class AdditionalHandler
{
    /**
     * @var int
     */
    private $internalID;

    /**
     * @var array
     */
    private array $data;
    private string $bundle;

    /**
     * @param int $internalID ID of either movie or show.
     */
  public function __construct(int $internalID, string $bundle)
  {
      $this->internalID = $internalID;
      $this->bundle = $bundle;
      $this->find();
  }

    /**
     * @return void
     */
  private function find(): void
  {
      $this->data = Query::query("SELECT * FROM additional_information WHERE internal_id = $this->internalID AND bundle = '$this->bundle'")[0] ?? [];
  }

    /**
     * @return bool
     */
    public function isAdded(): bool
  {
      return !empty($this->data);
  }

    /**
     * @return int
     */
    public function getTMID(): int
  {
      return $this->data['tm_id'] ?? 0;
  }

    /**
     * @return float|int
     */
    public function getPopularity(): float|int
  {
      return $this->data['popularity'] ?? 0;
  }

    /**
     * @return float|int
     */
    public function getVoteAverage(): float|int
  {
      return $this->data['vote_average'] ?? 0;
  }

    /**
     * @return float|int
     */
    public function getVoteCount(): float|int
  {
      return $this->data['vote_count'] ?? 0;
  }

    /**
     * @return string|null
     */
    public function getLanguage(): string|null
  {
      return $this->data['original_language'] ?? null;
  }


    /**
     * @return string|null
     */
    public function getCountry(): string|null
  {
      return $this->data['origin_country'] ?? null;
  }

    /**
     * @return array
     */
    public function getTrailers(): array
  {
      return explode(',', $this->data['trailer_videos'] ?? "");
  }

    /**
     * @return string|null
     */
    public function getBundle(): string|null
  {
      return $this->data['bundle'] ?? null;
  }

    /**
     * @return array
     */
    public function getValues(): array
  {
      return $this->data;
  }

}