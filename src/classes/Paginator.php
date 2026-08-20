<?php
namespace CT275\Labs;

class Paginator
{
    public int $recordsPerPage;
    public int $totalRecords;
    public int $currentPage;
    public int $totalPages;
    public int $recordOffset;

    public function __construct(int $recordsPerPage, int $totalRecords, int $currentPage = 1)
    {
        $this->recordsPerPage = $recordsPerPage;
        $this->totalRecords = $totalRecords;
        $this->currentPage = max(1, $currentPage);
        $this->totalPages = (int)ceil($totalRecords / $recordsPerPage);
        $this->recordOffset = ($this->currentPage - 1) * $recordsPerPage;
    }

    public function getPrevPage(): int|bool { return $this->currentPage > 1 ? $this->currentPage - 1 : false; }
    public function getNextPage(): int|bool { return $this->currentPage < $this->totalPages ? $this->currentPage + 1 : false; }
    public function getPages(int $length = 3): array {
        $half = floor($length / 2);
        $start = max(1, $this->currentPage - $half);
        $end = min($this->totalPages, $this->currentPage + $half);
        return range($start, $end);
    }
}
