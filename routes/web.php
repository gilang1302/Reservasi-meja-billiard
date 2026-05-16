use Illuminate\Support\Facades\Route;

Route::get('/customer', function () {
    return view('dashboard.customer');
});

Route::get('/owner', function () {
    return view('dashboard.owner');
});

Route::get('/operator', function () {
    return view('dashboard.operator');
});

Route::get('/reservation', function () {
    return view('reservation');
});
