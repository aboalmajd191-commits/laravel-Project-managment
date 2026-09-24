# CLAUDE.md — NGO Services Management System (dashFinsh)
> آخر تحديث: 2026-05-03 — يعكس الحالة الفعلية للمشروع بعد توحيد الستايل وإعادة هيكلة الخدمات

---

## نظرة عامة

نظام إدارة خدمات مؤسسة غير ربحية مبني على **Laravel 11 + MySQL**.
ثلاثة أدوار: `admin` / `project_manager` / `data_entry`.

---

## Tech Stack

| المكوّن | التقنية |
|---|---|
| Backend | Laravel 11 (PHP 8.2+) |
| Database | MySQL 8+ |
| Frontend | Blade + Alpine.js + TailwindCSS v4 (Vite) |
| Auth | LoginController يدوي — بدون Breeze |
| Charts | Chart.js |
| File Upload | Laravel Storage (disk: `private`) |
| Fonts | Tajawal (Google Fonts) |

---

## هيكل المجلدات الفعلي

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/LoginController.php
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   └── UserController.php
│   │   ├── Manager/
│   │   │   ├── DashboardController.php
│   │   │   ├── ProjectController.php
│   │   │   ├── ProjectStatusController.php
│   │   │   ├── ApprovalController.php
│   │   │   ├── TrainingController.php
│   │   │   ├── EconomicController.php
│   │   │   ├── AwarenessWorkshopController.php
│   │   │   ├── LegalConsultationController.php
│   │   │   ├── PsychologicalConsultationController.php
│   │   │   ├── JudicialRepresentationController.php
│   │   │   ├── LegalRepresentationController.php
│   │   │   ├── MediationController.php
│   │   │   ├── IndividualSupportSessionController.php
│   │   │   └── GroupSupportSessionController.php
│   │   └── DataEntry/
│   │       ├── UserSubmissionController.php
│   │       ├── TrainingController.php
│   │       ├── EconomicController.php
│   │       ├── AwarenessWorkshopController.php
│   │       ├── LegalConsultationController.php
│   │       ├── PsychologicalConsultationController.php
│   │       ├── JudicialRepresentationController.php
│   │       ├── LegalRepresentationController.php
│   │       ├── MediationController.php
│   │       ├── IndividualSupportSessionController.php
│   │       ├── GroupSupportSessionController.php
│   │       └── BlankServiceController.php
│   ├── Requests/
│   │   ├── Manager/ (ProjectRequest, ApprovalRejectRequest, EconomicRequest)
│   │   └── DataEntry/ (TrainingRequest, EconomicRequest, MediationRequest, etc.)
│   └── Middleware/
│       ├── RoleMiddleware.php
│       └── CheckAccountActive.php
├── Models/ (10+ Service Models + User + Project)
└── Services/
    └── ApprovalService.php      ← مركز إدارة الموافقات والارتباط بالموديلات
```

---

## الميزات والتحسينات الحديثة (تحديث مايو 2026)

### 1. توحيد واجهات المدخلات (UI Standardization)
تم تحديث ملف `app.css` لفرض ستايل موحد وجريء لجميع عناصر النماذج (Inputs, Selects, Textareas):
- **الخلفية**: صلبة بيضاء (`bg-white`) لضمان الوضوح التام.
- **الحدود**: حدود واضحة بلون `slate-200` مع تظليل خفيف (`shadow-sm`).
- **التركيز (Focus)**: حدود بلون Indigo مع "توهج" خارجي (`ring`) لتمييز الحقل النشط.
- **التناسق**: أحجام خطوط وهوامش داخلية موحدة في كافة صفحات النظام.

### 2. مركزية منطق الموافقات (ApprovalService Refactoring)
تم سحب منطق الربط بين مفاتيح الخدمات وموديلاتها إلى `ApprovalService`:
- توحيد مصفوفة الموديلات في مكان واحد `getServiceModels()`.
- إضافة توابع مساعدة لجلب السجلات بأسمائها وعلاقاتها تلقائياً.
- تحديث `ProjectController` و `ApprovalController` ليعتمدا على هذا الـ Service، مما يسهل إضافة خدمات جديدة لاحقاً.

### 3. الامتثال لقواعد البرمجة (Code Style Compliance)
- تفعيل `declare(strict_types=1);` في معظم الـ Controllers الأساسية.
- الانتقال التدريجي لاستخدام `Form Requests` للتحقق من البيانات بدلاً من التحقق داخل الـ Controller.
- توحيد مسميات المفاتيح (مثل `economic_empowerment`) عبر الـ Routes والـ Services والـ Views.

---

## UI Design System (Tailwind v4)

```
Primary Indigo:  #4f46e5 (primary)        → أزرار رئيسية، Focus states
Emerald Success: #10b981 (accent)         → موافقات، شارات نجاح
Amber Warning:   #f59e0b (warning)        → قيد الانتظار (Pending)
Rose Danger:     #ef4444 (danger)         → رفض، حذف
Surface:         #f8fafc (surface)        → خلفية الصفحات
Sidebar:         #1e293b (sidebar)        → خلفية القائمة الجانبية
```

**قواعد التصميم:**
- **Inputs**: يجب أن تكون واضحة وليست شفافة. استخدم `rounded-xl` و `border-slate-200`.
- **Fonts**: الخط الرئيسي هو **Tajawal** لضمان قراءة ممتازة للغة العربية.
- **RTL**: النظام مصمم بالكامل `dir="rtl"` مع مراعاة الهوامش الجانبية (Padding) في القائمة الجانبية اليمنى.

---

## قواعد البرمجة الصارمة

1. **Strict Types**: إضافة `declare(strict_types=1);` في كل ملف PHP جديد.
2. **Form Requests**: ممنوع استخدام `$request->validate()` داخل الـ Controller؛ استخدم ملف Request مخصص.
3. **Parent/Child Pattern**: المشاريع (Templates) تخزن بـ `parent_id = null` والردود التفصيلية بـ `parent_id = ProjectID`.
4. **Visibility**: الحقول في `Data Entry` يجب أن تكون `nullable` بقدر الإمكان لتسهيل الإدخال السريع.
5. **Security**: لا يمكن تعديل الطلبات المقبولة (`approved`) من قبل مدخلي البيانات.

---

## ملاحظات للمطورين (AI Agents)
- عند إضافة خدمة جديدة، يجب تسجيلها في `ApprovalService::getServiceModels()`.
- تأكد من أن الـ View يستخدم مصفوفة `match` الصحيحة في صفحة الموافقات لعرض الاسم العربي للخدمة.
- المرفقات يتم التعامل معها عبر Trait `HasServiceAttachments` لتوحيد منطق الرفع والحذف.