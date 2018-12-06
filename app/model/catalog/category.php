<?php

class ModelCatalogcategory extends Model
{
    public function addcategory($data)
    {
        $this->db->query("INSERT INTO category SET name = '" . $this->db->escape($data['name']) . "',create_time = NOW()");

        return $this->db->getLastId();
    }

    public function editCategory($category_id, $data)
    {
        $this->db->query("UPDATE category SET name = '" . $this->db->escape($data['name']) . "', date_modified = NOW() WHERE id = '" . (int)$category_id . "'");

        return $this->db->countAffected();
    }

    public function deleteCategory($category_id)
    {
        $this->db->query("DELETE FROM category WHERE id = '" . (int)$category_id . "'");
        return $this->db->countAffected();
    }

    public function getCategoryByName($name)
    {
        $query = $this->db->query("SELECT * FROM category WHERE name = '" . $this->db->escape($name) . "'");
        return $query->row;
    }

    public function getCategory($id)
    {
        $query = $this->db->query("SELECT * FROM ccategory  WHERE id = '" . (int)$id . "'");

        return $query->row;
    }

    public function getCategories($data = array())
    {
        $sql = "SELECT * FROM category";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'create_time'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY create_time";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCategories($data)
    {
        $sql = "SELECT COUNT(1) AS total FROM category";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAllCategories()
    {
        $cache_key = 'cache:categories';
        $categories = $this->cache->get($cache_key);
        if (!$categories) {
            $query = $this->db->query("SELECT id,name FROM category");
            $categories = $query->rows;
            $this->cache->set($cache_key, $categories, 3600);
        }
        return $categories;
    }
}

?>