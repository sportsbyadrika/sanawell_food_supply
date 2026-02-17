<?php

class CustomerCategoryController extends BaseController
{
   public function index(): void
{
    Auth::requireAgencyAdmin();

    $model = new CustomerCategory();
    $categories = $model->allByAgency(Auth::user()['agency_id']);
  
    $this->render('agency/customer_categories', [
        'categories' => $categories,
       
        'csrf_token' => Csrf::token()
    ]);
}

  public function store(): void
{
    Auth::requireAgencyAdmin();

    if (!Csrf::verify($_POST['_csrf_token'] ?? '')) {
        http_response_code(403);
        echo 'Invalid CSRF token';
        return;
    }

    $model = new CustomerCategory();

    $model->create([
        'agency_id'  => Auth::user()['agency_id'],
        'name'       => trim($_POST['name'] ?? ''),
        'description'=> trim($_POST['description'] ?? ''),
    ]);

    $this->redirect('index.php?route=customer_categories');
}
   

    public function edit(): void
{
    Auth::requireAgencyAdmin();

    $id = (int)($_GET['id'] ?? 0);

    $model = new CustomerCategory();
    $category = $model->find($id);

    if (!$category) {
        die('Category not found');
    }

    $this->render('agency/customer_categories_edit', [
        'category' => $category,
        'csrf_token' => Csrf::token()
    ]);
}


    public function update(): void
{
    Auth::requireAgencyAdmin();

    if (!Csrf::verify($_POST['_csrf_token'] ?? '')) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }

    $model = new CustomerCategory();

    $model->update((int)$_POST['id'], [
        'name' => trim($_POST['name']),
        'description' => trim($_POST['description'])
    ]);

    $this->redirect('index.php?route=customer_categories');
}

public function toggle()
{
    if (!isset($_GET['id'])) {
        header("Location: index.php?route=customer_categories");
        exit;
    }

    $id = (int) $_GET['id'];

    $model = new CustomerCategory();
    $model->toggle($id);

    header("Location: index.php?route=customer_categories");
    exit;
}
}
