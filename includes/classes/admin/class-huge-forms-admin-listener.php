<?php

/**
 */
class Huge_Forms_Admin_Listener
{
    /**
     * @param string $page
     * @param string $task
     * @param string $method
     * @return bool
     */
    protected static function is_request($page, $task, $method = 'GET')
    {
        return ($_SERVER['REQUEST_METHOD'] === $method && isset($_GET['page']) && $_GET['page'] === $page && isset($_GET['task']) && $_GET['task'] === $task);
    }

}