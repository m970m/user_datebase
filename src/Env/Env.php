<?php
declare(strict_types=1);

namespace App\Env;

use App\Database\DbSourceType;

class Env
{
    private ?DbSourceType $dbSourceType = null;

    public function __construct(private string $env) {}

    public function getDbSourceType(): DbSourceType
    {
        if (is_null($this->dbSourceType))
        {
            $this->dbSourceType = $this->parseDbSource($this->env);
        }

        return $this->dbSourceType;
    }

    private function parseDbSource(string $env)
    {
        if (($fd = fopen($env, 'r')) === false)
        {
            throw new \Exception('env file does not exist');
        }
        try
        {
            while (!feof($fd))
            {
                $line = fgets($fd);
                $param = explode('=', $line);
                if (!isset($param[0]) || !isset($param[1]) || $param[0] !== 'DB_SOURCE')
                {
                    continue;
                }

                if ($param[1] === 'mysql')
                {
                    return DbSourceType::MYSQL;
                }

                if ($param[1] === 'json')
                {
                    return DbSourceType::JSON;
                }
            }

            throw new \Exception('env file does not contain param');
        } catch (\Exception $ex)
        {
            fclose($fd);
            throw $ex;
        }
    }
}