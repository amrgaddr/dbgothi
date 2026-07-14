<?php
// Start session and check login
session_start();

// If not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Now include the rest of your page
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج الفاتورة</title>
    
    <!-- Tailwind -->
    <script src="./scripts/tailwind/tailwind.js"></script>
    
    <!-- HTMX -->
    <script src="./htmx/htmx.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        .rtl { direction: rtl; }
        .rtl select { background-position: left 0.75rem center; }
        .htmx-indicator {
            opacity: 0;
            transition: opacity 500ms ease-in;
        }
        .htmx-request .htmx-indicator {
            opacity: 1;
        }
        .htmx-request.htmx-indicator {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4 rtl">

    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl p-6 md:p-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            إنشاء فاتورة جديدة
        </h2>

        <form 
            hx-post="save_invoice.php" 
            hx-target="#response" 
            hx-swap="innerHTML"
            hx-trigger="submit"
            hx-indicator="#submit-spinner"
            class="space-y-5"
        >
            <!-- Company Dropdown (value = com_name, data-id = com_id) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                

                <!-- Hospital Dropdown (value = hospital_name) -->
                <div class="relative">
                    <label for="hospital_name" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                        المستشفى <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select 
                            id="hospital_name" 
                            name="hospital_name" 
                            required
                            hx-get="get_hospitals.php"
                            hx-trigger="load"
                            hx-swap="innerHTML"
                            hx-target="#hospital_name"
                            class="w-full rounded-lg border-slate-300 border bg-white py-2.5 px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                        >
                            <option value="">جاري تحميل المستشفيات...</option>
                        </select>
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none text-slate-400">
                            <i class="fas fa-hospital"></i>
                        </span>
                    </div>
                </div>




                            <!-- Invoice Section
            <div>
                <label for="invoice_section" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                    القسم <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="invoice_section" 
                    name="invoice_section" 
                    required
                    placeholder="مثال: قسم الطوارئ"
                    class="w-full rounded-lg border-slate-300 border bg-white py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                >
            </div> -->







                        <!-- Invoice Type Dropdown -->
            <div>
                <label for="invoice_section" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                    القسم  <span class="text-red-500">*</span>
                </label>
                <select 
                    id="invoice_section" 
                    name="invoice_section" 
                    required
                    class="w-full rounded-lg border-slate-300 border bg-white py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                >
                    <option value="">-- اختر النوع --</option>
                    <option value="الأدوية">الأدوية</option>
                    <option value="المستلزمات">المستلزمات</option>
                    <option value="الصيانة الطبية">الصيانة الطبية</option>
                    <option value="الصيانة غير الطبية">الصيانة غير الطبية</option>
                    <option value="المطبوعات والاحبار">المطبوعات والاحبار</option>
                    <option value="النظافة">النظافة</option>
                    <option value="تغذية عاملين">تغذية عاملين</option>
                    <option value="تغذية مرضى">تغذية مرضى</option>
                </select>
            </div>

            </div>




            <div class="relative">
                    <label for="com_id" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                        الشركة <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select 
                            id="com_id" 
                            name="com_name" 
                            required
                            hx-get="get_companies.php"
                            hx-trigger="load"
                            hx-swap="innerHTML"
                            hx-target="#com_id"
                            class="w-full rounded-lg border-slate-300 border bg-white py-2.5 px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                        >
                            <option value="">جاري تحميل الشركات...</option>
                        </select>
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none text-slate-400">
                            <i class="fas fa-building"></i>
                        </span>
                    </div>
                    <!-- Hidden field to store com_id -->
                    <input type="hidden" id="com_id_hidden" name="com_id" value="">
                </div>



            <!-- Invoice Date (simple input type="date") and Invoice Number -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                        تاريخ الفاتورة <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="invoice_date" 
                        name="invoice_date" 
                        required
                        class="w-full rounded-lg border-slate-300 border bg-white py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                    >
                </div>
                <div>
                    <label for="invoice_number" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                        رقم الفاتورة <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="invoice_number" 
                        name="invoice_number" 
                        required
                        placeholder="مثال: 1024"
                        class="w-full rounded-lg border-slate-300 border bg-white py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                    >
                </div>
            </div>

            <!-- Invoice Cost -->
            <div>
                <label for="invoice_cost" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                    تكلفة الفاتورة <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                    <input 
                        type="number" 
                        id="invoice_cost" 
                        name="invoice_cost" 
                        required
                        step="0.01"
                        placeholder="0.00"
                        class="w-full rounded-lg border-slate-300 border bg-white py-2.5 pr-10 pl-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                    >
                </div>
            </div>

            <!-- Issued Radio -->
            <div>
                <span class="block text-sm font-medium text-slate-700 mb-1 text-right">
                    حالة الإصدار <span class="text-red-500">*</span>
                </span>
                <div class="flex flex-wrap items-center gap-6 bg-slate-50 p-3 rounded-lg border border-slate-200">
                    <label class="inline-flex items-center gap-2 text-slate-700 cursor-pointer">
                        <input type="radio" name="issued" value="مسسد" checked class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <span>مسسد</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-slate-700 cursor-pointer">
                        <input type="radio" name="issued" value="غيرمسدد" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <span>غير مسدد</span>
                    </label>
                </div>
            </div>

            <!-- Invoice Order Date and Order Number -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="invoice_order_date" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                        تاريخ أمر الدفع
                    </label>
                    <input 
                        type="date" 
                        id="invoice_order_date" 
                        name="invoice_order_date"
                        class="w-full rounded-lg border-slate-300 border bg-white py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                    >
                </div>
                <div>
                    <label for="invoice_order_number" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                        رقم أمر الدفع
                    </label>
                    <input 
                        type="number" 
                        id="invoice_order_number" 
                        name="invoice_order_number"
                        placeholder="رقم الأمر"
                        class="w-full rounded-lg border-slate-300 border bg-white py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                    >
                </div>
            </div>

            <!-- Invoice Type Dropdown -->
            <div>
                <label for="invoice_type" class="block text-sm font-medium text-slate-700 mb-1 text-right">
                    نوع الفاتورة <span class="text-red-500">*</span>
                </label>
                <select 
                    id="invoice_type" 
                    name="invoice_type" 
                    required
                    class="w-full rounded-lg border-slate-300 border bg-white py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition text-right"
                >
                    <option value="">-- اختر النوع --</option>
                    <option value="مجاني">مجاني</option>
                    <option value="اقتصادي">اقتصادي</option>
                    <option value="نفقة">نفقة</option>
                    <option value="تأمين">تأمين</option>
                    <option value="قوائم">قوائم</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex flex-col sm:flex-row gap-3 items-center justify-between border-t border-slate-200">
                <button 
                    type="submit" 
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-8 rounded-lg shadow-md transition flex items-center justify-center gap-2"
                >
                    <i class="fas fa-paper-plane"></i> 
                    حفظ الفاتورة
                    <svg id="submit-spinner" class="htmx-indicator animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>

            <!-- Response -->
            <div id="response" class="mt-4 text-sm font-medium text-right"></div>
        </form>
    </div>

    <script>
        // Get company ID from selected option's data-id attribute
        document.getElementById('com_id')?.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const id = selectedOption.getAttribute('data-id') || '';
            document.getElementById('com_id_hidden').value = id;
        });
    </script>

</body>
</html>