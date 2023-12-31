
<?php
class Product {
    private $id;
    private $title;
    private $description;
    private $thumbnail;
    private $price;

    public function __construct($id, $title, $description, $thumbnail, $price) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->thumbnail = $thumbnail;
        $this->price = $price;
    }

    public static function createProduct(PDO $db, $title, $description, $thumbnail, $price) {
        $stmt = $db->prepare("INSERT INTO gym_equipment (title, description, thumbnail, price) VALUES (:title, :description, :thumbnail, :price)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':thumbnail', $thumbnail);
        $stmt->bindParam(':price', $price);
        $stmt->execute();
    }

    // Update an existing product in the database
    public static function updateProduct(PDO $db, $productId, $title, $description, $thumbnail, $price) {
        $stmt = $db->prepare("UPDATE gym_equipment SET title = :title, description = :description, thumbnail = :thumbnail, price = :price WHERE id = :id");
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':thumbnail', $thumbnail);
        $stmt->bindParam(':price', $price);
        $stmt->execute();
    }
    

    // Getters for the product properties 
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getThumbnail() {
        return $this->thumbnail;
    }

    public function getPrice() {
        return $this->price;
    }
}
