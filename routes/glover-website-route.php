<?php


use App\Http\Livewire\Extensions\GloverWebsite\Livewire\Auth\LoginLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\Auth\RegisterLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\Auth\ForgotPasswordLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\Auth\ResetPasswordLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\SettingsLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\WelcomeLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\VendorTypeLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\VendorType\PackageLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\VendorType\TaxiLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\ProductLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\ServiceLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\ServiceBookingLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\VendorLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\CartLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\CheckoutLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\MyOrdersLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\ProfileLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\DeliveryAddressLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\CategoryLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\CategoriesLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\SearchLivewire;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\ServiceVendorLivewire;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//admin routes
Route::group(['middleware' => ['web']], function () {
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/website/settings', SettingsLivewire::class)->name('glover-website.admin.settings');
    });
});

Route::group(['middleware' => ['web'], 'domain' => env('APP_WEBSITE_DOMAIN')], function () {

    //error pages
    Route::get('/404', function () {
        return view('livewire.extensions.glover-website.errors.404');
    })->name('glover-website.404');
    // index
    Route::get('/', WelcomeLivewire::class)->name('glover-website.index');
    //LOGIN & REGISTER
    Route::get('/login', LoginLivewire::class)->name('glover-website.login');
    Route::get('/register', RegisterLivewire::class)->name('glover-website.register');
    Route::get('/password/forgot', ForgotPasswordLivewire::class)->name('glover-website.password.forgot');
    Route::get('/password/reset/{token}', ResetPasswordLivewire::class)->name('glover-website.password.reset');
    //
    Route::get('/cart', CartLivewire::class)->name('glover-website.cart');
    //
    Route::get('/vendor/type/{id}/{slug}', VendorTypeLivewire::class)->name('glover-website.vendor.type');
    Route::get('/vendor/{id}/{slug}', VendorLivewire::class)->name('glover-website.vendor');
    Route::get('/service/vendor/{id}/{slug}', ServiceVendorLivewire::class)->name('glover-website.service.vendor');
    Route::get('/category/{id}/{slug}', CategoryLivewire::class)->name('glover-website.category');
    Route::get('/categories', CategoriesLivewire::class)->name('glover-website.categories');
    Route::get('/product/{id}/{slug}', ProductLivewire::class)->name('glover-website.product');
    Route::get('/service/{id}/{slug}', ServiceLivewire::class)->name('glover-website.service');

    //search
    Route::get('/search', SearchLivewire::class)->name('glover-website.search');


    //auth routes
    Route::group(['middleware' => ['auth']], function () {
        //
        Route::get('/profile', ProfileLivewire::class)->name('glover-website.profile');
        Route::get('/orders', MyOrdersLivewire::class)->name('glover-website.orders');
        Route::get('/delivery/addresses', DeliveryAddressLivewire::class)->name('glover-website.addresses');
        Route::get('/logout', function (Request $request) {
            auth()->logout();
            return redirect()->intended(route('glover-website.index'));
        })->name('glover-website.logout');
        //
        Route::get('/taxi/order', TaxiLivewire::class)->name('glover-website.vendor.type.taxi');
        Route::get('/package/order', PackageLivewire::class)->name('glover-website.vendor.type.parcel');
        Route::get('/parcel/order', PackageLivewire::class)->name('glover-website.vendor.type.package');
        Route::get('/booking/service/{id}/{slug}', ServiceBookingLivewire::class)->name('glover-website.service.booking');
        //checkout
        Route::get('/checkout', CheckoutLivewire::class)->name('glover-website.checkout');
    });
});
