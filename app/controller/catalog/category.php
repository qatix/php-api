<?php

/**
 * 分类
 */
class ControllerCatalogCategory extends Controller
{
    public function add()
    {
        $result = array();
        $result['status'] = 1;

        if ($this->request->is_post()) {
            $data = $this->request->post;

            $this->load->model('catalog/category');

            //判断名称是否合适
            if ($result['status']) {
                if (empty($data['name'])) {
                    $result['status'] = 0;
                    $result['error'] = '分类名称不能为空';
                } else if (utf8_strlen($data['name']) > 64) {
                    $result['status'] = 0;
                    $result['error'] = '分类名称不能超过64个字';
                }
            }


            if ($result['status']) {
                $category = $this->model_catalog_category->getCategoryyByName($data['name']);
                if (!empty($category)) {
                    $result['status'] = 0;
                    $result['error'] = '已存在相同名称的分类';
                }
            }

            if ($result['status']) {
                $data['name'] = trim($data['name']);
                $id = $this->model_catalog_category->addcategory($data);
                if (!$id) {
                    $result['status'] = 0;
                    $result['error'] = '添加分类失败';
                } else {
                    $result['id'] = $id;
                }
            }
        } else {
            $result['status'] = 0;
            $result['error'] = 'method not support';
        }

        $this->response->setOutput(json_encode($result));
    }

    public function update()
    {
        $result = array();
        $result['status'] = 1;

        if ($this->request->is_post()) {
            $data = $this->request->post;

            $this->load->model('catalog/category');

            if ($result['status']) {
                if (!empty($data['id'])) {
                    $category = $this->model_catalog_category->getCategoryy($data['id']);
                }

                if (empty($category)) {
                    $result['status'] = 0;
                    $result['error'] = '分类未找到';
                }
            }

            //判断名称是否合适
            if ($result['status']) {
                if (empty($data['name'])) {
                    $result['status'] = 0;
                    $result['error'] = '分类名称不能为空';
                } else if (utf8_strlen($data['name']) > 64) {
                    $result['status'] = 0;
                    $result['error'] = '分类名称不能超过64个字';
                }
            }
            if ($result['status']) {
                $category_by_name = $this->model_catalog_category->getCategoryByName($data['name']);
                if (!empty($category_by_name) && $category_by_name['id'] != $category['id']) {
                    $result['status'] = 0;
                    $result['error'] = '已存在相同名称的分类';
                }
            }

            if ($result['status']) {
                $data['name'] = trim($data['name']);
                $result_data = $this->model_catalog_category->editcategory($data['id'], $data);
                if (!$result_data) {
                    $result['status'] = 0;
                    $result['error'] = '更新分类失败';
                }
            }
        } else {
            $result['status'] = 0;
            $result['error'] = 'method not support';
        }

        $this->response->setOutput(json_encode($result));
    }

    public function get()
    {
        $result = array();
        $result['status'] = 1;

        if ($this->request->is_get()) {
            $data = $this->request->get;

            $this->load->model('catalog/category');
            if ($result['status']) {
                if (!empty($data['id'])) {
                    $category = $this->model_catalog_category->getCategory($data['id']);
                }

                if (empty($category)) {
                    $result['status'] = 0;
                    $result['error'] = '分类未找到';
                }
            }

            if ($result['status']) {
                $result['category'] = $category;
            }
        } else {
            $result['status'] = 0;
            $result['error'] = 'method not support';
        }

        $this->response->setOutput(json_encode($result));
    }

    public function gets()
    {
        $result = array();
        $result['status'] = 1;
        if ($this->request->is_get()) {
            $data = $this->request->get;

            if ($result['status']) {

                if (isset($data['search_key'])) {
                    $filter_search_key = $data['search_key'];
                } else {
                    $filter_search_key = null;
                }

                if (isset($data['category_id'])) {
                    $filter_category_id = $data['category_id'];
                } else {
                    $filter_category_id = null;
                }

                if (isset($data['sort'])) {
                    $sort = $data['sort'];
                } else {
                    $sort = 'create_time';
                }

                if (isset($data['order'])) {
                    $order = $data['order'];
                } else {
                    $order = 'DESC';
                }

                if (!empty($data['page'])) {
                    $page = $data['page'];
                } else {
                    $page = 1;
                }

                if (!empty($data['limit'])) {
                    $page_limit = $data['limit'];
                } else {
                    $page_limit = DEFAULT_PAGE_LIMIT;
                }

                $result['data'] = array();

                $filter_data = array(
                    'filter_name' => $filter_search_key,
                    'filter_category_id' => $filter_category_id,
                    'sort' => $sort,
                    'order' => $order,
                    'start' => ($page - 1) * $page_limit,
                    'limit' => $page_limit
                );

                $this->load->model('catalog/category');

                if ($page == 1) {
                    $category_total = $this->model_catalog_category->getTotalCategories($filter_data);
                    $result['count'] = (int)$category_total;
                }

                $category_results = $this->model_catalog_category->getCategories($filter_data);

                foreach ($category_results as $category_result) {
                    $product = array(
                        'id' => $category_result['id'],
                        'name' => $category_result['name'],
                        'create_time' => $category_result['create_time']
                    );

                    $result['data'][] = $product;
                }
            }
        } else {
            $result['status'] = 0;
            $result['error'] = 'method not support';
        }

        $this->response->setOutput(json_encode($result));
    }

    public function delete()
    {
        $result = array();
        $result['status'] = 1;

        if ($this->request->is_post()) {

            $this->load->model('catalog/category');
            $data = $this->request->post;

            if ($result['status']) {
                if (!empty($data['id'])) {
                    $category = $this->model_catalog_category->getCategoryy($data['id']);
                }

                if (empty($category)) {
                    $result['status'] = 0;
                    $result['error'] = '分类未找到';
                }
            }

            if ($result['status']) {
                $result_data = $this->model_catalog_category->deleteCategory($data['id']);
                if (!$result_data) {
                    $result['status'] = 0;
                    $result['error'] = '删除分类失败';
                }
            }
        } else {
            $result['status'] = 0;
            $result['error'] = 'method not support';
        }

        $this->response->setOutput(json_encode($result));
    }
}

?>