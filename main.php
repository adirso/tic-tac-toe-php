<?php

class XO
{
    /**
     * @var array
     */
    private $table;

    /**
     * @var bool
     */
    private $finished;

    /**
     * @var int
     */
    private $player;

    public function __construct()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->table = [[null, null, null], [null, null, null], [null, null, null]];
        $this->finished = false;
        $this->player = 1;
    }

    public function run()
    {
        $this->printTable();
        while (!$this->finished) {
            $position = (int)readline("Enter your position between 1 to 9: ");

            if (!$position || !($position >= 1 && $position <= 9)) {
                continue;
            }

            try {
                $this->place($position);
                $this->printTable();
                $this->changePlayer();
            } catch (Exception $e) {
                echo "\n" . $e->getMessage();
                continue;
            }
        }
    }

    /**
     * @return void
     */
    private function printTable()
    {
        foreach ($this->table as $row) {
            echo "\n";
            foreach ($row as $colum) {
                echo sprintf("[%s]", $colum);
            }
        }
        echo "\n";
    }

    /**
     * @param $position
     * @return void
     * @throws Exception
     */
    private function place($position)
    {
        $indexes = $this->getIndexesFromPosition($position);

        if ($this->table[$indexes[0]][$indexes[1]]) {
            throw new Exception("This box is taken, please pick another one");
        }

        $this->table[$indexes[0]][$indexes[1]] = $this->player;

        $this->checkWin($indexes[0], $indexes[1]);
    }

    /**
     * @param int $position
     * @return int[]
     */
    private function getIndexesFromPosition(int $position): array
    {
        $indexes = [
            1 => [0, 0],
            2 => [0, 1],
            3 => [0, 2],
            4 => [1, 0],
            5 => [1, 1],
            6 => [1, 2],
            7 => [2, 0],
            8 => [2, 1],
            9 => [2, 2],
        ];

        return $indexes[$position];
    }

    /**
     * @param int $row
     * @param int $column
     * @return void
     * @throws Exception
     */
    private function checkWin(int $row, int $column)
    {
        if ($this->checkWinColumn($row) ||
            $this->checkWinRow($column) ||
            $this->checkWinLeftSlant($row, $column) ||
            $this->checkWinRightSlant($row, $column)) {

            $this->printTable();
            $this->finished = true;
            throw new Exception(sprintf("Player %s won", $this->player));
        }
    }

    /**
     * @param int $column
     * @return bool
     */
    private function checkWinColumn(int $column): bool
    {
        return (
            $this->table[0][$column] == $this->table[1][$column] &&
            $this->table[1][$column] == $this->table[2][$column] &&
            $this->table[0][$column] != null
        );
    }

    /**
     * @param int $row
     * @return bool
     */
    private function checkWinRow(int $row): bool
    {
        return (
            $this->table[$row][0] == $this->table[$row][1] &&
            $this->table[$row][1] == $this->table[$row][2] &&
            $this->table[$row][0] != null
        );
    }

    /**
     * @param int $row
     * @param int $column
     * @return bool
     */
    private function checkWinLeftSlant(int $row, int $column): bool
    {
        return (
            $row + $column == 2 &&
            $this->table[0][2] == $this->table[2][0] &&
            $this->table[0][2] == $this->table[1][1] &&
            $this->table[1][1] != null
        );
    }

    /**
     * @param int $row
     * @param int $column
     * @return bool
     */
    private function checkWinRightSlant(int $row, int $column): bool
    {
        return (
            $row == $column &&
            $this->table[0][0] == $this->table[1][1] &&
            $this->table[0][0] == $this->table[2][2] &&
            $this->table[1][1] != null
        );
    }

    /**
     * @return void
     */
    private function changePlayer()
    {
        $this->player = ($this->player == 1) ? 0 : 1;
    }
}

$game = new XO();
$game->run();