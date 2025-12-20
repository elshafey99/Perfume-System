# تصميم قاعدة البيانات - نظام إدارة محل العطور
## Database Design - Perfume Shop Management System

---

## 📊 نظرة عامة على قاعدة البيانات

### العلاقات الأساسية:
```
Customers ──< Sales ──< SaleItems >── Products
                │
                └──> Payments

Products ──< Inventory
         └──< CompositionIngredients >── Compositions
         └──< PurchaseItems >── Purchases >── Suppliers

Employees ──< Sales
         └──< Inventory (created_by)

Branches ──< Inventory
         └──< BranchTransfers
```

---

## 🗄️ الجداول التفصيلية

### 1. جدول الفئات (categories)

```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,              -- الاسم بالعربية
    name_en VARCHAR(255) NULL,               -- الاسم بالإنجليزية
    parent_id BIGINT UNSIGNED NULL,          -- للفئات الفرعية
    icon VARCHAR(255) NULL,                  -- أيقونة الفئة
    description TEXT NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_parent_id (parent_id),
    INDEX idx_is_active (is_active)
);
```

**أنواع الفئات:**
- عطور جاهزة (Ready-made Perfumes)
- زيوت عطرية خام (Raw Essential Oils)
- كحول (Alcohol)
- زجاجات فارغة (Empty Bottles)
- تغليف (Packaging)
- مثبتات (Fixatives)
- مستلزمات (Accessories)

---

### 2. جدول المنتجات (products)

```sql
CREATE TABLE products (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    name_en VARCHAR(255) NULL,
    sku VARCHAR(100) UNIQUE NULL,             -- Stock Keeping Unit
    barcode VARCHAR(100) UNIQUE NULL,        -- الباركود
    category_id BIGINT UNSIGNED NOT NULL,
    
    -- نوع المنتج
    type ENUM('ready_made', 'raw_oil', 'alcohol', 'bottle', 'packaging', 'fixative', 'accessory') NOT NULL,
    
    -- وحدات القياس
    unit_type ENUM('piece', 'gram', 'ml', 'tola', 'quarter_tola') NOT NULL DEFAULT 'piece',
    conversion_rate DECIMAL(10, 4) DEFAULT 1, -- معدل التحويل للوحدة الأساسية
    
    -- المخزون
    current_stock DECIMAL(10, 4) DEFAULT 0,
    min_stock_level DECIMAL(10, 4) DEFAULT 0,  -- حد الأمان
    max_stock_level DECIMAL(10, 4) NULL,
    
    -- الأسعار
    cost_price DECIMAL(10, 2) DEFAULT 0,      -- سعر التكلفة
    selling_price DECIMAL(10, 2) DEFAULT 0,   -- سعر البيع
    price_per_gram DECIMAL(10, 2) NULL,      -- سعر الجرام (للزيوت)
    price_per_ml DECIMAL(10, 2) NULL,        -- سعر المليلتر
    
    -- معلومات إضافية
    image VARCHAR(255) NULL,
    description TEXT NULL,
    brand VARCHAR(255) NULL,                  -- الماركة (للعطور الجاهزة)
    
    -- خصائص خاصة
    is_raw_material BOOLEAN DEFAULT FALSE,    -- هل هو مادة خام؟
    is_composition BOOLEAN DEFAULT FALSE,     -- هل هو تركيبة؟
    is_active BOOLEAN DEFAULT TRUE,
    can_return BOOLEAN DEFAULT TRUE,          -- قابل للاسترجاع؟
    
    -- معلومات المورد
    supplier_id BIGINT UNSIGNED NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL,
    INDEX idx_category_id (category_id),
    INDEX idx_type (type),
    INDEX idx_barcode (barcode),
    INDEX idx_sku (sku),
    INDEX idx_is_active (is_active)
);
```

---

### 3. جدول التركيبات/الوصفات (compositions)

```sql
CREATE TABLE compositions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,               -- اسم التركيبة
    name_en VARCHAR(255) NULL,
    code VARCHAR(100) UNIQUE NULL,           -- كود التركيبة
    
    -- معلومات التركيبة
    product_id BIGINT UNSIGNED NULL,         -- المنتج النهائي (إن وجد)
    bottle_size DECIMAL(10, 2) NOT NULL,      -- حجم الزجاجة (ml)
    concentration_type ENUM('EDP', 'EDT', 'Parfum', 'Cologne') NULL,
    
    -- التكلفة والأسعار
    base_cost DECIMAL(10, 2) DEFAULT 0,      -- تكلفة المواد الخام
    service_fee DECIMAL(10, 2) DEFAULT 0,    -- رسوم الخدمة/التركيب
    selling_price DECIMAL(10, 2) DEFAULT 0,  -- سعر البيع النهائي
    
    -- معلومات إضافية
    instructions TEXT NULL,                   -- تعليمات التركيب
    notes TEXT NULL,
    image VARCHAR(255) NULL,
    
    -- نوع الوصفة
    is_magic_recipe BOOLEAN DEFAULT FALSE,   -- هل هي وصفة سحرية؟
    original_perfume_name VARCHAR(255) NULL, -- اسم العطر الأصلي (للوصفات السحرية)
    
    -- الحالة
    is_active BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    INDEX idx_product_id (product_id),
    INDEX idx_is_magic_recipe (is_magic_recipe),
    INDEX idx_is_active (is_active)
);
```

---

### 4. جدول مكونات التركيبة (composition_ingredients)

```sql
CREATE TABLE composition_ingredients (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    composition_id BIGINT UNSIGNED NOT NULL,
    ingredient_product_id BIGINT UNSIGNED NOT NULL,  -- المنتج المكون
    quantity DECIMAL(10, 4) NOT NULL,                -- الكمية
    unit ENUM('gram', 'ml', 'piece') NOT NULL,        -- الوحدة
    sort_order INT DEFAULT 0,                         -- ترتيب المكون
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (composition_id) REFERENCES compositions(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredient_product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_composition_id (composition_id),
    INDEX idx_ingredient_product_id (ingredient_product_id),
    UNIQUE KEY unique_composition_ingredient (composition_id, ingredient_product_id)
);
```

---

### 5. جدول العملاء (customers)

```sql
CREATE TABLE customers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,       -- رقم الجوال (فريد)
    email VARCHAR(255) UNIQUE NULL,
    birth_date DATE NULL,
    gender ENUM('male', 'female', 'other') NULL,
    address TEXT NULL,
    
    -- نظام الولاء
    loyalty_points DECIMAL(10, 2) DEFAULT 0,
    loyalty_level ENUM('bronze', 'silver', 'gold', 'platinum') DEFAULT 'bronze',
    total_purchases DECIMAL(10, 2) DEFAULT 0,  -- إجمالي المشتريات
    last_purchase_date DATE NULL,
    
    -- التفضيلات (JSON)
    preferred_scents JSON NULL,              -- ['woody', 'sweet', 'oud']
    favorite_products JSON NULL,             -- IDs of favorite products
    
    -- معلومات إضافية
    notes TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_phone (phone),
    INDEX idx_email (email),
    INDEX idx_loyalty_level (loyalty_level),
    INDEX idx_is_active (is_active)
);
```

---

### 6. جدول المبيعات (sales)

```sql
CREATE TABLE sales (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(100) UNIQUE NOT NULL,  -- رقم الفاتورة
    
    -- العلاقات
    customer_id BIGINT UNSIGNED NULL,             -- العميل (قد يكون null للعملاء غير المسجلين)
    employee_id BIGINT UNSIGNED NOT NULL,         -- الموظف/البائع
    branch_id BIGINT UNSIGNED NULL,              -- الفرع
    
    -- المبالغ
    subtotal DECIMAL(10, 2) DEFAULT 0,           -- المجموع الفرعي
    discount DECIMAL(10, 2) DEFAULT 0,            -- الخصم
    discount_type ENUM('amount', 'percentage') NULL,
    tax_rate DECIMAL(5, 2) DEFAULT 0,            -- نسبة الضريبة
    tax_amount DECIMAL(10, 2) DEFAULT 0,         -- قيمة الضريبة
    total DECIMAL(10, 2) DEFAULT 0,              -- الإجمالي النهائي
    
    -- الدفع
    payment_method ENUM('cash', 'card', 'bank_transfer', 'apple_pay', 'split') NOT NULL,
    payment_status ENUM('pending', 'paid', 'partial', 'refunded') DEFAULT 'paid',
    paid_amount DECIMAL(10, 2) DEFAULT 0,
    
    -- معلومات إضافية
    sale_date DATETIME NOT NULL,
    notes TEXT NULL,
    
    -- حالة الفاتورة
    status ENUM('completed', 'cancelled', 'refunded') DEFAULT 'completed',
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE RESTRICT,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
    INDEX idx_invoice_number (invoice_number),
    INDEX idx_customer_id (customer_id),
    INDEX idx_employee_id (employee_id),
    INDEX idx_sale_date (sale_date),
    INDEX idx_status (status)
);
```

---

### 7. جدول عناصر المبيعات (sale_items)

```sql
CREATE TABLE sale_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    sale_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NULL,              -- المنتج (قد يكون null للتركيبات المخصصة)
    composition_id BIGINT UNSIGNED NULL,         -- التركيبة (إن وجدت)
    
    -- معلومات المنتج
    product_name VARCHAR(255) NOT NULL,           -- اسم المنتج (للاحتفاظ بالاسم وقت البيع)
    quantity DECIMAL(10, 4) NOT NULL,
    unit ENUM('piece', 'gram', 'ml', 'tola', 'quarter_tola') NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,          -- سعر الوحدة
    total DECIMAL(10, 2) NOT NULL,                -- الإجمالي
    
    -- معلومات إضافية
    is_composition BOOLEAN DEFAULT FALSE,         -- هل هو تركيبة؟
    is_custom_blend BOOLEAN DEFAULT FALSE,        -- هل هو خلطة مخصصة؟
    notes TEXT NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    FOREIGN KEY (composition_id) REFERENCES compositions(id) ON DELETE SET NULL,
    INDEX idx_sale_id (sale_id),
    INDEX idx_product_id (product_id),
    INDEX idx_composition_id (composition_id)
);
```

---

### 8. جدول المرتجعات (returns)

```sql
CREATE TABLE returns (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    return_number VARCHAR(100) UNIQUE NOT NULL,
    sale_id BIGINT UNSIGNED NOT NULL,
    sale_item_id BIGINT UNSIGNED NULL,           -- عنصر محدد من الفاتورة
    
    -- معلومات المرتجع
    return_reason ENUM('defective', 'wrong_item', 'customer_request', 'other') NOT NULL,
    return_type ENUM('refund', 'exchange', 'store_credit') NOT NULL,
    return_amount DECIMAL(10, 2) NOT NULL,
    
    -- حالة المرتجع
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    
    -- معلومات إضافية
    notes TEXT NULL,
    processed_by BIGINT UNSIGNED NULL,            -- الموظف الذي قام بالمعالجة
    processed_at DATETIME NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE RESTRICT,
    FOREIGN KEY (sale_item_id) REFERENCES sale_items(id) ON DELETE SET NULL,
    FOREIGN KEY (processed_by) REFERENCES employees(id) ON DELETE SET NULL,
    INDEX idx_sale_id (sale_id),
    INDEX idx_status (status)
);
```

---

### 9. جدول المخزون (inventory_transactions)

```sql
CREATE TABLE inventory_transactions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NULL,
    
    -- نوع الحركة
    type ENUM('sale', 'purchase', 'return', 'adjustment', 'transfer_in', 'transfer_out', 'composition', 'waste') NOT NULL,
    quantity DECIMAL(10, 4) NOT NULL,             -- الكمية (موجب للإضافة، سالب للخصم)
    unit ENUM('piece', 'gram', 'ml', 'tola', 'quarter_tola') NOT NULL,
    
    -- المرجع
    reference_type VARCHAR(50) NULL,              -- 'sale', 'purchase', 'composition', etc.
    reference_id BIGINT UNSIGNED NULL,            -- ID من الجدول المرجعي
    
    -- معلومات إضافية
    notes TEXT NULL,
    created_by BIGINT UNSIGNED NULL,              -- الموظف الذي قام بالعملية
    transaction_date DATETIME NOT NULL,
    
    -- المخزون بعد الحركة
    stock_after DECIMAL(10, 4) NOT NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES employees(id) ON DELETE SET NULL,
    INDEX idx_product_id (product_id),
    INDEX idx_type (type),
    INDEX idx_reference (reference_type, reference_id),
    INDEX idx_transaction_date (transaction_date)
);
```

---

### 10. جدول الموردين (suppliers)

```sql
CREATE TABLE suppliers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255) NULL,
    phone VARCHAR(20) NULL,
    email VARCHAR(255) NULL,
    address TEXT NULL,
    
    -- معلومات إضافية
    tax_number VARCHAR(100) NULL,
    notes TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_is_active (is_active)
);
```

---

### 11. جدول المشتريات (purchases)

```sql
CREATE TABLE purchases (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(100) UNIQUE NULL,     -- رقم فاتورة المورد
    supplier_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NULL,
    
    -- المبالغ
    subtotal DECIMAL(10, 2) DEFAULT 0,
    tax_amount DECIMAL(10, 2) DEFAULT 0,
    total DECIMAL(10, 2) DEFAULT 0,
    
    -- معلومات إضافية
    purchase_date DATE NOT NULL,
    expected_delivery_date DATE NULL,
    received_date DATE NULL,
    notes TEXT NULL,
    
    -- الحالة
    status ENUM('pending', 'received', 'cancelled') DEFAULT 'pending',
    
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE RESTRICT,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES employees(id) ON DELETE SET NULL,
    INDEX idx_supplier_id (supplier_id),
    INDEX idx_purchase_date (purchase_date),
    INDEX idx_status (status)
);
```

---

### 12. جدول عناصر المشتريات (purchase_items)

```sql
CREATE TABLE purchase_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    purchase_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    
    quantity DECIMAL(10, 4) NOT NULL,
    unit ENUM('piece', 'gram', 'ml', 'tola', 'quarter_tola') NOT NULL,
    cost_price DECIMAL(10, 2) NOT NULL,          -- سعر التكلفة
    total DECIMAL(10, 2) NOT NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_purchase_id (purchase_id),
    INDEX idx_product_id (product_id)
);
```

---

### 13. جدول المصاريف (expenses)

```sql
CREATE TABLE expenses (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    category ENUM('rent', 'salaries', 'electricity', 'shipping', 'marketing', 'maintenance', 'other') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description TEXT NULL,
    expense_date DATE NOT NULL,
    receipt_image VARCHAR(255) NULL,              -- صورة الإيصال
    
    branch_id BIGINT UNSIGNED NULL,
    created_by BIGINT UNSIGNED NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES employees(id) ON DELETE SET NULL,
    INDEX idx_category (category),
    INDEX idx_expense_date (expense_date),
    INDEX idx_branch_id (branch_id)
);
```

---

### 14. جدول الموظفين (employees)

```sql
CREATE TABLE employees (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,                 -- ربط بحساب المستخدم (إن وجد)
    branch_id BIGINT UNSIGNED NULL,
    
    -- معلومات الموظف
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    email VARCHAR(255) NULL,
    position VARCHAR(100) NULL,                   -- المنصب
    hire_date DATE NULL,
    salary DECIMAL(10, 2) NULL,
    
    -- الصلاحيات (JSON)
    permissions JSON NULL,                         -- ['can_discount', 'can_view_reports', etc.]
    
    -- الحالة
    is_active BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_branch_id (branch_id),
    INDEX idx_is_active (is_active)
);
```

---

### 15. جدول الفروع (branches)

```sql
CREATE TABLE branches (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE NULL,                 -- كود الفرع
    address TEXT NULL,
    phone VARCHAR(20) NULL,
    email VARCHAR(255) NULL,
    is_main BOOLEAN DEFAULT FALSE,                -- هل هو الفرع الرئيسي؟
    is_active BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_is_main (is_main),
    INDEX idx_is_active (is_active)
);
```

---

### 16. جدول النقل بين الفروع (branch_transfers)

```sql
CREATE TABLE branch_transfers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    transfer_number VARCHAR(100) UNIQUE NOT NULL,
    from_branch_id BIGINT UNSIGNED NOT NULL,
    to_branch_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    
    quantity DECIMAL(10, 4) NOT NULL,
    unit ENUM('piece', 'gram', 'ml', 'tola', 'quarter_tola') NOT NULL,
    
    transfer_date DATE NOT NULL,
    received_date DATE NULL,
    
    status ENUM('pending', 'in_transit', 'received', 'cancelled') DEFAULT 'pending',
    notes TEXT NULL,
    
    created_by BIGINT UNSIGNED NULL,
    received_by BIGINT UNSIGNED NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (from_branch_id) REFERENCES branches(id) ON DELETE RESTRICT,
    FOREIGN KEY (to_branch_id) REFERENCES branches(id) ON DELETE RESTRICT,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES employees(id) ON DELETE SET NULL,
    FOREIGN KEY (received_by) REFERENCES employees(id) ON DELETE SET NULL,
    INDEX idx_from_branch_id (from_branch_id),
    INDEX idx_to_branch_id (to_branch_id),
    INDEX idx_status (status)
);
```

---

### 17. جدول نقاط الولاء (loyalty_points)

```sql
CREATE TABLE loyalty_points (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    customer_id BIGINT UNSIGNED NOT NULL,
    points DECIMAL(10, 2) NOT NULL,               -- النقاط (موجب للإضافة، سالب للخصم)
    type ENUM('earned', 'redeemed', 'expired', 'adjusted') NOT NULL,
    reference_type VARCHAR(50) NULL,              -- 'sale', 'redemption', etc.
    reference_id BIGINT UNSIGNED NULL,
    balance_after DECIMAL(10, 2) NOT NULL,        -- الرصيد بعد العملية
    expires_at DATE NULL,                          -- تاريخ انتهاء النقاط
    notes TEXT NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX idx_customer_id (customer_id),
    INDEX idx_type (type),
    INDEX idx_expires_at (expires_at)
);
```

---

### 18. جدول الإشعارات (notifications)

```sql
CREATE TABLE notifications (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    type ENUM('low_stock', 'birthday', 'loyalty_reminder', 'promotion', 'system') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    
    -- المستلم
    recipient_type ENUM('admin', 'employee', 'customer') NOT NULL,
    recipient_id BIGINT UNSIGNED NULL,            -- ID المستلم
    
    -- الحالة
    is_read BOOLEAN DEFAULT FALSE,
    read_at DATETIME NULL,
    
    -- معلومات إضافية (JSON)
    data JSON NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_recipient (recipient_type, recipient_id),
    INDEX idx_is_read (is_read),
    INDEX idx_type (type)
);
```

---

### 19. جدول سجل العمليات (audit_logs)

```sql
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_type ENUM('admin', 'employee') NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    action VARCHAR(100) NOT NULL,                  -- 'create', 'update', 'delete', 'view'
    model_type VARCHAR(100) NOT NULL,             -- 'Product', 'Sale', etc.
    model_id BIGINT UNSIGNED NULL,
    old_values JSON NULL,                         -- القيم القديمة
    new_values JSON NULL,                          -- القيم الجديدة
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    
    created_at TIMESTAMP NULL,
    
    INDEX idx_user (user_type, user_id),
    INDEX idx_model (model_type, model_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);
```

---

## 🔗 العلاقات المهمة

### علاقة التركيبات:
```
Composition (1) ──< CompositionIngredients (N) >── Products (N)
```

### علاقة المبيعات:
```
Customer (1) ──< Sales (N) ──< SaleItems (N) >── Products (N)
                                    └──> Compositions (N)
```

### علاقة المخزون:
```
Products (1) ──< InventoryTransactions (N)
```

### علاقة المشتريات:
```
Supplier (1) ──< Purchases (N) ──< PurchaseItems (N) >── Products (N)
```

---

## 📝 ملاحظات مهمة

1. **وحدات القياس:** يجب التحويل بين الوحدات عند الحاجة (مثلاً: 1 تولة = 11.66 جرام)
2. **المخزون:** يتم تحديثه تلقائياً عند كل عملية (بيع، شراء، تركيبة)
3. **التكلفة:** يتم حسابها تلقائياً للتركيبات بناءً على أسعار المواد الخام
4. **الباركود:** يتم توليده تلقائياً للتركيبات المخصصة
5. **النسخ الاحتياطي:** يجب عمل نسخ احتياطي يومي لقاعدة البيانات

---

**تاريخ الإنشاء:** 2025-01-27  
**آخر تحديث:** 2025-01-27

