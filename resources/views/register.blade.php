<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل حساب جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #4a6cf7;
            box-shadow: 0 0 0 0.25rem rgba(74, 108, 247, 0.25);
        }
        .btn-primary {
            background-color: #4a6cf7;
            border: none;
            padding: 10px 25px;
        }
        .user-type-option {
            cursor: pointer;
            transition: all 0.3s;
        }
        .user-type-option.active {
            background-color: #4a6cf7;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container register-container">
        <h2 class="text-center mb-4">إنشاء حساب جديد</h2>
        <form id="registerForm">
            <!-- الحقول الأساسية -->
            <div class="mb-3">
                <label for="name" class="form-label">الاسم الكامل</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="text-muted">يجب أن تحتوي على 8 أحرف على الأقل</small>
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            
            <!-- نوع المستخدم -->
            <div class="mb-4">
                <label class="form-label">نوع الحساب</label>
                <div class="d-flex gap-2">
                    <div class="user-type-option p-2 border rounded flex-grow-1 text-center" data-type="job_seeker">
                        باحث عن عمل
                    </div>
                    <div class="user-type-option p-2 border rounded flex-grow-1 text-center" data-type="employer">
                        صاحب عمل
                    </div>
                    <div class="user-type-option p-2 border rounded flex-grow-1 text-center" data-type="admin">
                        مدير
                    </div>
                </div>
                <input type="hidden" name="type" id="userType" value="job_seeker" required>
            </div>
            
            <!-- حقول صاحب العمل (تظهر فقط عند الاختيار) -->
            <div id="employerFields" style="display: none;">
                <div class="mb-3">
                    <label for="company_name" class="form-label">اسم الشركة</label>
                    <input type="text" class="form-control" id="company_name" name="company_name">
                </div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">تسجيل الحساب</button>
            </div>
            
            <div class="text-center mt-3">
                <p>لديك حساب بالفعل؟ <a href="/login">تسجيل الدخول</a></p>
            </div>
        </form>
    </div>

    <script>
        // اختيار نوع المستخدم
        document.querySelectorAll('.user-type-option').forEach(option => {
            option.addEventListener('click', function() {
                // إزالة التنشيط من جميع الخيارات
                document.querySelectorAll('.user-type-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                
                // تنشيط الخيار المحدد
                this.classList.add('active');
                
                // تحديث القيمة المخفية
                document.getElementById('userType').value = this.dataset.type;
                
                // إظهار/إخفاء حقول صاحب العمل
                if (this.dataset.type === 'employer') {
                    document.getElementById('employerFields').style.display = 'block';
                    document.getElementById('company_name').required = true;
                } else {
                    document.getElementById('employerFields').style.display = 'none';
                    document.getElementById('company_name').required = false;
                }
            });
        });
        
        // تنشيط خيار باحث عن عمل افتراضيًا
        document.querySelector('[data-type="job_seeker"]').classList.add('active');
        
        // إرسال النموذج
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                name: this.name.value,
                email: this.email.value,
                password: this.password.value,
                password_confirmation: this.password_confirmation.value,
                type: this.userType.value,
                company_name: this.company_name?.value || null
            };
            
            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    alert('تم التسجيل بنجاح!');
                    // حفظ التوكن وإعادة التوجيه
                    localStorage.setItem('token', data.token);
                    window.location.href = '/dashboard';
                } else {
                    // عرض رسائل الخطأ
                    let errorMsg = 'حدث خطأ أثناء التسجيل';
                    if (data.errors) {
                        errorMsg = Object.values(data.errors).join('\n');
                    } else if (data.message) {
                        errorMsg = data.message;
                    }
                    alert(errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال بالخادم');
            }
        });
    </script>
</body>
</html>