<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';

Route::group(['namespace' => 'App\Livewire\Auth'], function () {
    // Add your routes here
    Route::get('login', 'Login\AuthLoginIndex')->name('login');
    Route::get('register', 'Register\AuthRegisterIndex')->name('register');
});

Route::group(['namespace' => 'App\Livewire\Admin', 'prefix' => 'user', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', 'Dashboard\AdminDashboardIndex')->name('user.dashboard');

    Route::group(['namespace' => 'Registration', 'prefix' => 'registration'], function () {
        Route::get('/new', 'New\AdminRegistrationNewIndex')->name('user.registration.new');
        Route::get('/appointments', 'Appointments\AdminRegistrationAppointmentsIndex')->name('user.registration.appointments');
        Route::get('/queue', 'Queue\AdminRegistrationQueueIndex')->name('user.registration.queue');
    });

    Route::group(['namespace' => 'Consultation', 'prefix' => 'consultation'], function () {
        Route::get('/patient', 'Patient\AdminConsultationPatientIndex')->name('user.consultation.patient');
        Route::get('/patient/detail', 'Patient\Detail\AdminConsultationPatientDetailIndex')->name('user.consultation.patient.detail');
        Route::get('/consultation', 'Consultation\AdminConsultationConsultationIndex')->name('user.consultation.consultation');
        Route::get('/consultation/detail', 'Consultation\Detail\AdminConsultationConsultationDetailIndex')->name('user.consultation.consultation.detail');
        Route::get('/history', 'History\AdminConsultationHistoryIndex')->name('user.consultation.history');
        Route::get('/history/detail', 'History\Detail\AdminConsultationHistoryDetailIndex')->name('user.consultation.history.detail');
    });

    Route::group(['namespace' => 'Purchase', 'prefix' => 'purchase'], function () {
        Route::get('/defecta', 'Defecta\AdminPurchaseDefectaIndex')->name('user.purchase.defecta');
        Route::get('/draft-mail-order', 'Draft\MailOrder\AdminPurchaseDraftMailOrderIndex')->name('user.purchase.draft-mail-order');
        Route::get('/mail-order', 'MailOrder\AdminPurchaseMailOrderIndex')->name('user.purchase.mail-order');
        Route::get('/mail-order/detail', 'MailOrder\Detail\AdminPurchaseMailOrderDetailIndex')->name('user.purchase.mail-order.detail');
    });

    Route::group(['namespace' => 'Logistic', 'prefix' => 'logistic'], function () {
        Route::get('/good-come', 'GoodCome\AdminLogisticGoodComeIndex')->name('user.logistic.good-come');
        Route::get('/good-come/detail', 'GoodCome\Detail\AdminLogisticGoodComeDetailIndex')->name('user.logistic.good-come.detail');
        Route::get('/product-stock', 'ProductStock\AdminLogisticProductStockIndex')->name('user.logistic.product-stock');
        Route::get('/stock-in', 'StockIn\AdminLogisticStockInIndex')->name('user.logistic.stock-in');
        Route::get('/stock-out', 'StockOut\AdminLogisticStockOutIndex')->name('user.logistic.stock-out');
        Route::get('/import-stock-product', 'ImportStockProduct\AdminLogisticImportStockProductIndex')->name('user.logistic.import-stock-product');
        Route::get('/stock-product', 'StockProduct\AdminLogisticStockProductIndex')->name('user.logistic.stock-product');
        Route::get('/stock-product/detail', 'StockProduct\Detail\AdminLogisticStockProductDetailIndex')->name('user.logistic.stock-product.detail');
        Route::get('/return', 'Return\AdminLogisticReturnIndex')->name('user.purchase.return');
        Route::get('/dead-stock', 'DeadStock\AdminLogisticDeadStockIndex')->name('user.purchase.dead-stock');
        Route::get('/return/detail', 'Return\Detail\AdminLogisticReturnDetailIndex')->name('user.purchase.return.detail');
    });

    Route::group(['namespace' => 'Pharmacy', 'prefix' => 'pharmacy'], function () {
        Route::get('/consultation', 'Consultation\AdminPharmacyConsultationIndex')->name('user.pharmacy.consultation');
        Route::get('/consultation/detail', 'Consultation\Detail\AdminPharmacyConsultationDetailIndex')->name('user.pharmacy.consultation.detail');
        Route::get('/sale', 'Sale\AdminPharmacySaleIndex')->name('user.pharmacy.sale');
        Route::get('/sale/detail', 'Sale\Detail\AdminPharmacySaleDetailIndex')->name('user.pharmacy.sale.detail');
        Route::get('/sale/recipe', 'Sale\Recipe\AdminPharmacySaleRecipeIndex')->name('user.pharmacy.sale.recipe');
        Route::get('/take-medicine', 'TakeMedicine\AdminPharmacyTakeMedicineIndex')->name('user.pharmacy.take-medicine');
        Route::get('/take-medicine/detail', 'TakeMedicine\Detail\AdminPharmacyTakeMedicineDetailIndex')->name('user.pharmacy.take-medicine.detail');
    });

    Route::group(['namespace' => 'Sale', 'prefix' => 'sale'], function () {
        Route::get('/price', 'Price\AdminSalePriceIndex')->name('user.sale.price');
        Route::get('/product-price', 'ProductPrice\AdminSaleProductPriceIndex')->name('user.sale.product-price');
        Route::get('/pos', 'Pos\AdminSalePosIndex')->name('user.sale.pos');
        Route::get('/pos/detail', 'Pos\Detail\AdminSalePosDetailIndex')->name('user.sale.pos.detail');
        Route::get('/pos/recipe', 'Pos\Recipe\AdminSalePosRecipeIndex')->name('user.sale.pos.recipe');
        Route::get('/report-sale', 'Report\AdminSaleReportIndex')->name('user.sale.report-sale');
        Route::get('/report-sale/detail', 'Report\Detail\AdminSaleReportDetailIndex')->name('user.sale.report-sale.detail');
        Route::get('/report-product-sale', 'ReportProduct\AdminSaleReportProductIndex')->name('user.sale.report-product-sale');
        Route::get('/report-payment', 'ReportPayment\AdminSaleReportPaymentIndex')->name('user.sale.report-payment');
        Route::get('/report-profit-loss', 'ReportProfitLoss\AdminSaleReportProfitLossIndex')->name('user.sale.report-profit-loss');
    });

    Route::group(['namespace' => 'Report', 'prefix' => 'report'], function () {
        Route::get('/incentive', 'Incentive\AdminReportIncentiveIndex')->name('user.report.incentive');
        Route::get('/stock', 'Stock\AdminReportStockIndex')->name('user.report.stock');
        Route::get('/in-stock', 'StockIn\AdminReportStockInIndex')->name('user.report.in-stock');
        Route::get('/out-stock', 'StockOut\AdminReportStockOutIndex')->name('user.report.out-stock');
        Route::get('/return-purchase', 'ReturPurchase\AdminReportReturnPurchaseIndex')->name('user.report.purchase.return');
        Route::get('/purchase', 'Purchase\AdminReportPurchaseIndex')->name('user.report.purchase');
        Route::get('/purchase/detail', 'Purchase\Detail\AdminReportPurchaseDetailIndex')->name('user.report.purchase.detail');
        Route::get('/product-purchase', 'PurchaseProduct\AdminReportPurchaseProductIndex')->name('user.report.product.purchase');
        Route::get('/goods-come', 'GoodsCome\AdminReportGoodsComeIndex')->name('user.report.goods-come');
        Route::get('/goods-come/detail', 'GoodsCome\Detail\AdminReportGoodsComeDetailIndex')->name('user.report.goods-come.detail');
        Route::get('/sale', 'Sale\AdminReportSaleIndex')->name('user.report.sale');
        Route::get('/sale/detail', 'Sale\Detail\AdminReportSaleDetailIndex')->name('user.report.sale.detail');
        Route::get('/product-sale', 'SaleProduct\AdminReportSaleProductIndex')->name('user.report.sale.product-sale');
        Route::get('/payment', 'Payment\AdminReportPaymentIndex')->name('user.report.payment');
        Route::get('/profit-loss', 'ProfitLoss\AdminReportProfitLossIndex')->name('user.report.profit.loss');
        Route::get('/dead-stock', 'DeadStock\AdminReportDeadStockIndex')->name('user.report.dead-stock');
        Route::get('/opname-stock', 'StockOpname\AdminReportStockOpnameIndex')->name('user.report.opname-stock');
        Route::get('/opname-stock/detail', 'StockOpname\Detail\AdminReportStockOpnameDetailIndex')->name('user.report.opname-stock.detail');
        Route::get('/product-stock-opname', 'StockOpnameProduct\AdminReportStockOpnameProductIndex')->name('user.report.product-stock-opname');
        // Route::get('/sale/detail', 'Report\Detail\AdminSaleReportDetailIndex')->name('user.report.sale.detail');
        // Route::get('/product-sale', 'ReportProduct\AdminSaleReportProductIndex')->name('user.report.product.sale');
        // Route::get('/payment', 'ReportPayment\AdminSaleReportPaymentIndex')->name('user.report.payment');
        // Route::get('/profit-loss', 'ReportProfitLoss\AdminSaleReportProfitLossIndex')->name('user.report.profit.loss');
    });

    Route::group(['namespace' => 'Finance', 'prefix' => 'finance'], function () {
        Route::get('/cost', 'Cost\AdminFinanceCostIndex')->name('user.finance.cost');
        Route::get('/cost/detail', 'Cost\Detail\AdminFinanceCostDetailIndex')->name('user.finance.cost.detail');
        Route::get('/finance', 'Finance\AdminFinanceFinanceIndex')->name('user.finance.finance');
        Route::get('/finance/detail', 'Finance\Detail\AdminFinanceFinanceDetailIndex')->name('user.finance.finance.detail');
        Route::get('/dead-stock', 'DeadStock\AdminFinanceDeadStockIndex')->name('user.finance.dead-stock');
        Route::get('/dead-stock/detail', 'DeadStock\Detail\AdminFinanceDeadStockDetailIndex')->name('user.finance.dead-stock.detail');
        Route::get('/stock-opname', 'StockOpname\AdminFinanceStockOpnameIndex')->name('user.finance.stock-opname');
        Route::get('/stock-opname/detail', 'StockOpname\Detail\AdminFinanceStockOpnameDetailIndex')->name('user.finance.stock-opname.detail');
        Route::get('/purchase', 'Purchase\AdminFinancePurchaseIndex')->name('user.finance.purchase');
        Route::get('/purchase/detail', 'Purchase\Detail\AdminFinancePurchaseDetailIndex')->name('user.finance.purchase.detail');
        Route::get('/sale', 'Sale\AdminFinanceSaleIndex')->name('user.finance.sale');
        Route::get('/sale/detail', 'Sale\Detail\AdminFinanceSaleDetailIndex')->name('user.finance.sale.detail');
        Route::get('/balance-sheet', 'BalanceSheet\AdminFinanceBalanceSheetIndex')->name('user.finance.balance-sheet');
        Route::get('/profit-loss', 'ProfitLoss\AdminFinanceProfitLossIndex')->name('user.finance.profit-loss');
        Route::get('/cash-flow', 'CashFlow\AdminFinanceCashFlowIndex')->name('user.finance.cash-flow');
        Route::get('/ledger', 'Ledger\AdminFinanceLedgerIndex')->name('user.finance.ledger');
        Route::get('/journal', 'Journal\AdminFinanceJournalIndex')->name('user.finance.journal');
    });

    Route::group(['namespace' => 'Master', 'prefix' => 'master'], function () {
        Route::group(['namespace' => 'Product', 'prefix' => 'product'], function () {
            Route::get('/detail', 'Detail\AdminMasterProductDetailIndex')->name('user.master.product.detail');
            Route::get('/detail/data', 'Detail\AdminMasterProductDetailData')->name('user.master.product.detail.data');
            Route::get('/package', 'Package\AdminMasterProductPackageIndex')->name('user.master.product.package');
            Route::get('/package/data', 'Package\AdminMasterProductPackageData')->name('user.master.product.package.data');
            Route::get('/category', 'Category\AdminMasterProductCategoryIndex')->name('user.master.product.category');
            Route::get('/factory', 'Factory\AdminMasterProductFactoryIndex')->name('user.master.product.factory');
            Route::get('/rack', 'Rack\AdminMasterProductRackIndex')->name('user.master.product.rack');
            Route::get('/unit', 'Unit\AdminMasterProductUnitIndex')->name('user.master.product.unit');
        });

        Route::group(['namespace' => 'Account', 'prefix' => 'account'], function () {
            Route::get('/account', 'Account\AdminMasterAccountAccountIndex')->name('user.master.account.account');
            Route::get('/category-account', 'CategoryAccount\AdminMasterAccountCategoryAccountIndex')->name('user.master.account.category-account');
        });
        Route::get('/recipe', 'Recipe\AdminMasterRecipeIndex')->name('user.master.recipe');
        Route::get('/how-to-use', 'HowToUse\AdminMasterHowToUseIndex')->name('user.master.how-to-use');
        Route::get('/recipe/detail', 'Recipe\Detail\AdminMasterRecipeDetailIndex')->name('user.master.recipe.detail');
        Route::get('/supplier', 'Supplier\AdminMasterSupplierIndex')->name('user.master.supplier');
        Route::get('/role', 'Role\AdminMasterRoleIndex')->name('user.master.role');
        Route::get('/user', 'User\AdminMasterUserIndex')->name('user.master.user');
        Route::get('/doctor', 'Doctor\AdminMasterDoctorIndex')->name('user.master.doctor');
        Route::get('/patient', 'Patient\AdminMasterPatientIndex')->name('user.master.patient');
        Route::get('/poly', 'Poly\AdminMasterPolyIndex')->name('user.master.poly');
        Route::get('/icd', 'Icd\AdminMasterIcdIndex')->name('user.master.icd');
        Route::get('/medicine-type', 'MedicineType\AdminMasterMedicineTypeIndex')->name('user.master.medicine-type');
        Route::get('/payment-method', 'PaymentMethod\AdminMasterPaymentMethodIndex')->name('user.master.payment-method');
        Route::get('/service-month', 'ServiceMonth\AdminMasterServiceMonthIndex')->name('user.master.service-month');
        Route::get('/doctor-control', 'DoctorControl\AdminMasterDoctorControlIndex')->name('user.master.doctor-control');
        Route::get('/action', 'Action\AdminMasterActionIndex')->name('user.master.action');
        Route::get('/discount', 'Discount\AdminMasterDiscountIndex')->name('user.master.discount');
        Route::get('/promotion', 'Promotion\AdminMasterPromotionIndex')->name('user.master.promotion');
        Route::get('/promotion/detail', 'Promotion\Detail\AdminMasterPromotionDetailIndex')->name('user.master.promotion.detail');
        Route::get('/setting', 'Setting\AdminMasterSettingIndex')->name('user.master.setting');
    });
});

if (config('app.env') === 'local' || config('app.env') === 'development') {
    Route::redirect('', '/user');
}

Route::get('logout', function () {
    if (Auth::check()) {
        $user = User::find(auth()->user()->id);
        $user->update([
            'company_id' => null,
        ]);
    }
    auth()->logout();

    return redirect()->route('login');
})->name('logout');
