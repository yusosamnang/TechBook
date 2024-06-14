<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserBookController;

// Home route that checks authentication and role
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/books/{book}', [BookController::class, 'showDetail'])->name('books.showDetail');
//
Route::get('/category/{categoryId}', [HomeController::class, 'booksByCategory'])->name('category.books');
//
Route::get('/search', [SearchController::class, 'search'])->name('search');


// Authenticated user dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated user profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/books/{bookId}', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/user/books', [UserBookController::class, 'index'])->name('user.books.index');
    Route::get('/user/books/create', [UserBookController::class, 'create'])->name('user.books.create');
    Route::post('/user/books/store', [UserBookController::class, 'store'])->name('books.store');
    Route::get('/user/books/edit/{book}', [UserBookController::class, 'edit'])->name('user.books.edit');
    Route::put('/user/books/update/{book}', [UserBookController::class, 'update'])->name('books.update');
    Route::delete('/user/books/delete/{book}', [UserBookController::class, 'destroy'])->name('user.books.delete');

    // Listing all book requests
    Route::get('/book_requests', [BookRequestController::class, 'index'])->name('book_requests.index');

    // Storing a new book request
    Route::post('/book_requests', [BookRequestController::class, 'store'])->name('book_requests.store');

    // Adding routes for editing and deleting
    Route::get('/book_requests/{bookRequest}', [BookRequestController::class, 'edit'])->name('book_requests.edit');
    Route::put('/book_requests/{bookRequest}', [BookRequestController::class, 'update'])->name('book_requests.update');
    Route::delete('/book_requests/{bookRequest}', [BookRequestController::class, 'destroy'])->name('book_requests.destroy');

    // Cart routes
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'showSuccess'])->name('checkout.success');
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::post('/favorite/{id}/add', [FavoriteController::class, 'add'])->name('favorite.add');
    Route::post('/favorite/{id}/remove', [FavoriteController::class, 'remove'])->name('favorite.remove');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    Route::get('/pdfViewer', [BookController::class, 'viewPDF'])->name('pdf.viewer');

    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/markAsRead', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('markAsRead');
  // ... other routes ...
});

// Admin dashboard route

Route::get('/login/{provider}', [AuthenticatedSessionController::class, 'socialLogin'])->name('social.login');
Route::get('/login/{provider}/callback', [AuthenticatedSessionController::class, 'handleSocialCallback']);

Route::middleware('auth', 'admin')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/categories', [CategoryController::class, 'indexCategory'])->name('admin.categories');
    Route::get('/admin/categories/add', [CategoryController::class, 'addCategory']);
    Route::post('/admin/categories/addnew', [CategoryController::class, 'addNewCategory']);
    Route::get('/admin/categories/edit/{id}', [CategoryController::class, 'editCategory']);
    Route::post('/admin/categories/update/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/admin/categories/delete/{id}', [CategoryController::class, 'deleteCategory']);
    Route::get('/admin/books', [BookController::class, 'indexBook'])->name('admin.books');
    Route::get('/admin/books/add', [BookController::class, 'addBook']);
    Route::post('/admin/books/addnew', [BookController::class, 'addNewBook']);
    Route::get('/admin/books/edit/{id}', [BookController::class, 'editBook']);
    Route::post('/admin/books/update/{id}', [BookController::class, 'updateBook']);
    Route::delete('/admin/books/delete/{id}', [BookController::class, 'deleteBook']);
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/pending-books', [BookController::class, 'pendingBooks'])->name('admin.pending-books');
    Route::put('/admin/update-status/{id}', [BookController::class, 'updateStatus'])->name('admin.update-status');

    Route::get('/admin/feedbacks', [FeedbackController::class, 'index'])->name('admin.feedback.index');
    Route::post('/admin/feedbacks/{id}/approve', [FeedbackController::class, 'approve'])->name('admin.feedback.approve');
    Route::post('feedbacks/{id}/deny', [FeedbackController::class, 'deny'])->name('admin.feedback.deny');

    Route::get('/admin/book_request',[BookRequestController::class,'admin_index'])->name('admin.book_request');
    Route::post('/admin/book_request/{id}/approve', [BookRequestController::class, 'approve'])->name('admin.book_requests.approve');
    Route::post('book_request/{id}/deny', [BookRequestController::class, 'deny'])->name('admin.book_requests.deny');

    Route::get('/adminPdfViewer', [BookController::class, 'viewPDF']);
});


// Additional routes for other functionalities
require __DIR__.'/auth.php';