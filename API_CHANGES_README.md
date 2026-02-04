# API Changes & New Features - Frontend Integration Guide

## 📅 Date: 2026-02-03

---

## 🆕 New Features Summary

### 1. **Supplier Payments System**

### 2. **Daily Closings (تقفيل اليوم)**

### 3. **Open Price Support**

### 4. **Stock Display**

### 5. **QR Code & Print Labels**

---

## 1️⃣ Supplier Payments API

### **New Endpoints:**

#### **1.1 Get Supplier Payments**

```http
GET /api/suppliers/{supplier_id}/payments
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "amount": 500.0,
            "payment_date": "2026-02-01",
            "payment_method": "cash",
            "notes": "دفعة أولى",
            "creator": {
                "id": 1,
                "name": "أحمد محمد"
            }
        }
    ]
}
```

#### **1.2 Add Payment**

```http
POST /api/suppliers/{supplier_id}/payments
```

**Request:**

```json
{
    "amount": 500.0,
    "payment_date": "2026-02-01",
    "payment_method": "cash",
    "notes": "دفعة أولى"
}
```

#### **1.3 Get Supplier Statement**

```http
GET /api/suppliers/{supplier_id}/statement?from=2026-01-01&to=2026-02-01
```

#### **1.4 Get Supplier Balance**

```http
GET /api/suppliers/{supplier_id}/balance
```

**Response:**

```json
{
    "total_purchases": 5000.0,
    "total_paid": 3000.0,
    "balance_due": 2000.0
}
```

### **Modified Endpoints:**

#### **Supplier Resource - New Fields**

```http
GET /api/suppliers
GET /api/suppliers/{id}
```

**New Fields in Response:**

```json
{
    "phones": ["01234567890", "01098765432"],
    "website": "https://example.com",
    "area": "القاهرة",
    "total_purchases": 5000.0,
    "total_paid": 3000.0,
    "balance_due": 2000.0
}
```

#### **Create/Update Supplier - New Fields**

```http
POST /api/suppliers
PUT /api/suppliers/{id}
```

**New Fields in Request:**

```json
{
    "phones": ["01234567890", "01098765432"],
    "website": "https://example.com",
    "area": "القاهرة"
}
```

---

## 2️⃣ Daily Closings API (تقفيل اليوم)

### **New Endpoints:**

#### **2.1 Get Today's Data (قبل الإقفال)**

```http
GET /api/daily-closings/today
```

**Response:**

```json
{
    "date": "2026-02-03",
    "total_sales": 1550.0,
    "total_cash": 1200.0,
    "total_card": 350.0,
    "total_refunds": 0,
    "total_expenses": 200.0,
    "net_total": 1350.0,
    "is_closed": false
}
```

#### **2.2 Close Day**

```http
POST /api/daily-closings
```

**Request:**

```json
{
    "notes": "إقفال يوم الأحد - مبيعات ممتازة"
}
```

**Response:**

```json
{
    "id": 5,
    "closing_date": "2026-02-03",
    "total_sales": 1550.0,
    "total_cash": 1200.0,
    "total_card": 350.0,
    "total_invoices": 12,
    "total_refunds": 0,
    "total_expenses": 200.0,
    "net_total": 1350.0,
    "notes": "إقفال يوم الأحد",
    "closed_by": {
        "id": 1,
        "name": "أحمد محمد"
    }
}
```

#### **2.3 Get All Closings**

```http
GET /api/daily-closings?per_page=15
```

#### **2.4 Get Closing by ID**

```http
GET /api/daily-closings/{id}
```

#### **2.5 Get Closing by Date**

```http
GET /api/daily-closings/date/2026-02-03
```

---

## 3️⃣ Open Price Support

### **Modified Endpoints:**

#### **Product Resource - New Field**

```http
GET /api/products
GET /api/products/{id}
```

**New Field:**

```json
{
    "is_open_price": true // ⭐ يحتاج سعر مخصص
}
```

#### **Create/Update Product**

```http
POST /api/products
PUT /api/products/{id}
```

**New Field in Request:**

```json
{
    "is_open_price": true
}
```

#### **Create Sale - Custom Price**

```http
POST /api/sales
```

**New Field in Items:**

```json
{
    "items": [
        {
            "product_id": 1,
            "quantity": 2,
            "unit": "piece",
            "custom_price": 250.5 // ⭐ للمنتجات open_price
        }
    ]
}
```

**Notes:**

- إذا كان `is_open_price = true`، يمكن إرسال `custom_price`
- إذا لم يتم إرسال `custom_price`، يستخدم `selling_price` (قد يكون 0)

---

## 4️⃣ Stock Display

### **Modified Endpoints:**

#### **Product Resource - New Fields**

```http
GET /api/products
GET /api/products/{id}
```

**New Fields:**

```json
{
    "current_stock": 45.0,
    "is_low_stock": false,
    "is_open_price": false
}
```

**Usage في الكاشير:**

- عرض `current_stock` قبل إضافة المنتج للفاتورة
- تحذير إذا `is_low_stock = true`
- طلب سعر مخصص إذا `is_open_price = true`

---

## 5️⃣ QR Code & Print Labels

### **New Endpoints:**

#### **5.1 Generate QR Code**

```http
GET /api/products/{id}/qr-code
```

**Response:**

```json
{
    "product": {
        "id": 1,
        "name": "عطر فاخر",
        "code": "PROD-001",
        "sku": "SKU-001",
        "barcode": "1234567890"
    },
    "qr_code": "PD94bWwgdm...", // Base64 SVG
    "qr_code_type": "svg"
}
```

**Frontend Usage:**

```javascript
// Decode base64
const svgString = atob(response.data.qr_code);

// Display
document.getElementById("qr-container").innerHTML = svgString;
```

#### **5.2 Print Label**

```http
GET /api/products/{id}/print-label
```

**Response:**

```json
{
    "product": {
        "id": 1,
        "name": "عطر فاخر",
        "code": "PROD-001",
        "selling_price": 150.0,
        "category": "عطور جاهزة"
    },
    "qr_code": "PD94bWwgdm...", // Base64 SVG
    "qr_code_type": "svg",
    "barcode_data": "1234567890",
    "print_date": "2026-02-03 00:30:00"
}
```

---

## 6️⃣ Settings API Updates

### **Modified Endpoint:**

#### **Get Settings**

```http
GET /api/settings
```

**New Fields:**

```json
{
    "default_tax_rate": 15.0,
    "default_discount_rate": 0.0,
    "receipt_thank_you_message": "شكراً لزيارتكم - نتمنى رؤيتكم قريباً"
}
```

#### **Update Settings**

```http
POST /api/settings
```

**New Fields in Request:**

```json
{
    "default_tax_rate": 15.0,
    "default_discount_rate": 5.0,
    "receipt_thank_you_message": "شكراً لزيارتكم"
}
```

---

## 📋 Complete Endpoints Summary

### **New Endpoints (11):**

| Method | Endpoint                          | Purpose        |
| ------ | --------------------------------- | -------------- |
| GET    | `/api/suppliers/{id}/payments`    | قائمة الدفعات  |
| POST   | `/api/suppliers/{id}/payments`    | إضافة دفعة     |
| GET    | `/api/suppliers/{id}/statement`   | كشف الحساب     |
| GET    | `/api/suppliers/{id}/balance`     | الرصيد الحالي  |
| GET    | `/api/daily-closings/today`       | بيانات اليوم   |
| POST   | `/api/daily-closings`             | إقفال اليوم    |
| GET    | `/api/daily-closings`             | كل الإقفالات   |
| GET    | `/api/daily-closings/{id}`        | تفاصيل إقفال   |
| GET    | `/api/daily-closings/date/{date}` | إقفال بالتاريخ |
| GET    | `/api/products/{id}/qr-code`      | QR Code        |
| GET    | `/api/products/{id}/print-label`  | ملصق الطباعة   |

### **Modified Endpoints:**

| Endpoint              | New Fields                                                                  |
| --------------------- | --------------------------------------------------------------------------- |
| `GET /api/suppliers`  | `phones`, `website`, `area`, `total_purchases`, `total_paid`, `balance_due` |
| `POST /api/suppliers` | `phones`, `website`, `area`                                                 |
| `GET /api/products`   | `is_open_price`, `current_stock`, `is_low_stock`                            |
| `POST /api/products`  | `is_open_price`                                                             |
| `POST /api/sales`     | `items.*.custom_price`                                                      |
| `GET /api/settings`   | `default_tax_rate`, `default_discount_rate`, `receipt_thank_you_message`    |
| `POST /api/settings`  | `default_tax_rate`, `default_discount_rate`, `receipt_thank_you_message`    |

---

## 🎯 Frontend Implementation Checklist

### **Supplier Management:**

- [ ] عرض أرقام الهواتف (JSON array)
- [ ] عرض الموقع والمنطقة
- [ ] عرض الرصيد الحالي
- [ ] شاشة إضافة دفعة
- [ ] شاشة كشف الحساب

### **Daily Closings:**

- [ ] شاشة عرض بيانات اليوم (قبل الإقفال)
- [ ] زرار إقفال اليوم
- [ ] منع الإقفال المتكرر
- [ ] عرض تقارير الإقفالات السابقة

### **Open Price:**

- [ ] التحقق من `is_open_price` قبل إضافة منتج
- [ ] إظهار input للسعر إذا `is_open_price = true`
- [ ] تعطيل السعر إذا `is_open_price = false`

### **Stock Display:**

- [ ] عرض `current_stock` في الكاشير
- [ ] تحذير إذا `is_low_stock = true`
- [ ] منع البيع إذا الكمية غير كافية

### **QR Code & Print:**

- [ ] زر طباعة QR Code
- [ ] زر طباعة Label
- [ ] Decode base64 SVG
- [ ] عرض QR في modal أو صفحة طباعة

### **Settings:**

- [ ] إضافة نسبة الضريبة الافتراضية
- [ ] إضافة نسبة الخصم الافتراضية
- [ ] إضافة رسالة الشكر

---

## 💡 Important Notes

1. **QR Code Format**: SVG encoded as base64 - استخدم `atob()` للـ decode
2. **Phones Field**: JSON array - قد يحتوي على أكثر من رقم
3. **Open Price**: لو المنتج `is_open_price = true` ومفيش `custom_price` - هيستخدم `selling_price` (قد يكون 0)
4. **Daily Closing**: اليوم يُقفل مرة واحدة فقط - التكرار ممنوع
5. **Stock Check**: تحقق من `current_stock` قبل إضافة المنتج للفاتورة

---

**Last Updated:** 2026-02-03
