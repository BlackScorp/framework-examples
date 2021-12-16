<?php

namespace BlackScorp\Movies\Repository\Pdo;

use BlackScorp\Movies\Model\VideoModel;
use BlackScorp\Movies\Repository\VideoRepository;
use PDO;

final class PdoVideoRepository implements VideoRepository
{
    private PDO $PDO;

    private array $fields = [
        'id',
        'title',
        'overview',
        'releaseDate',
        'imagePath',
        'rentedTo',
        'owned'
    ];
    /**
     * @param PDO $PDO
     */
    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function add(VideoModel $video): void
    {
        $valuesPlaceholder = $this->fields;
        array_walk($valuesPlaceholder,function (&$value){
            $value = $value.'=VALUES('.$value.')';
        });

       $sql ="INSERT INTO movies (".implode(",",$this->fields).") VALUES(:".implode(',:',$this->fields).")
       ON DUPLICATE KEY UPDATE ".implode(',',$valuesPlaceholder);

       $statement = $this->PDO->prepare($sql);
       $statement->execute($video->toArray());
    }

    private function loadAllModels(bool $owned = true):array {
        $sql = "SELECT ".implode(",",$this->fields)." FROM movies WHERE owned = :owned";
        $statement = $this->PDO->prepare($sql);
        $statement->execute([':owned'=>$owned]);
        if($statement->rowCount() === 0){
            return [];
        }
        $statement->setFetchMode(PDO::FETCH_CLASS,VideoModel::class);
        return $statement->fetchAll();
    }
    public function findOwned(): array
    {
     return $this->loadAllModels();
    }

    public function findWished()
    {
        return $this->loadAllModels(false);
    }
}