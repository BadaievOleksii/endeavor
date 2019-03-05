<?php

namespace Endeavor\Tasks;

use Endeavor\Core\TaskProcessorInterface;

/**
 * Class DbQueryTaskProcessor
 *
 */
class DbQueryTaskProcessor implements TaskProcessorInterface
{

    /**
     * @param DbQueryTask $task
     *
     * @return \Generator
     */
    public function process($task)
    {
        $pdo = new \PDO(
            $task->connectionConfig['dsn'],
            $task->connectionConfig['user'],
            $task->connectionConfig['pass'],
            $task->connectionConfig['options']
        );

        $query = isset($task->query->scalar) ? $task->query->scalar : $task->query;
        
        if (!empty($query)) {
            $preparedQuery = $pdo->prepare($query);
            $preparedQuery->execute();
            
            while ($row = $preparedQuery->fetch(\PDO::FETCH_OBJ)) {
                yield $row;
            }
        }
        yield;
    }
}
