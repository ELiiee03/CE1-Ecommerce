<?php

class Product
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllProducts($page = 1)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $query = "SELECT * FROM products LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProducts($keyword, $page = 1, $perPage = 5, $categoryName = null, $sort = null)
    {
        $offset = ($page - 1) * $perPage;
    
        // Base query
        $query = "SELECT p.*, c.category_name 
                  FROM products p 
                  LEFT JOIN product_category c ON p.category_id = c.category_id 
                  WHERE (p.product_name LIKE :keyword OR p.description LIKE :keyword)";
    
        // Add category filtering only if a category is provided
        if (!empty($categoryName)) {
            $query .= " AND LOWER(c.category_name) = LOWER(:category_name)";
        }
    
        // Add sorting if provided
        if (!empty($sort)) {
            $sortParts = explode('-', $sort);
            if (count($sortParts) === 2) {
                $field = $sortParts[0];
                $direction = strtolower($sortParts[1]);
    
                $validFields = ['product_name', 'price', 'category_name'];
                $validDirections = ['asc', 'desc'];
    
                if (in_array($field, $validFields) && in_array($direction, $validDirections)) {
                    $query .= " ORDER BY $field " . strtoupper($direction);
                }
            }
        }
    
        // Pagination
        $query .= " LIMIT :limit OFFSET :offset";
    
        // Prepare statement
        $stmt = $this->db->prepare($query);
    
        // Bind parameters
        $keyword = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
    
        if (!empty($categoryName)) {
            $stmt->bindParam(':category_name', $categoryName, PDO::PARAM_STR);
        }
    
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    
        // Execute and fetch results
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
    
    
    










}

