# Family Bazar - Enhanced Data Models

---

## 1. User

Represents the app users (authentication and basic info).

| Field        | Type            | Description              |
| ------------ | --------------- | ------------------------ |
| id           | UUID / Integer  | Primary key              |
| name         | String          | Full name of the user    |
| email        | String          | Unique email address     |
| phone_number | String          | Contact number           |
| password     | String (hashed) | Hashed password          |
| avatar_url   | String          | Optional profile picture |
| created_at   | Timestamp       | Record creation time     |
| updated_at   | Timestamp       | Last updated time        |

---

## 2. Customer

Represents customers with delivery info (linked to User).

| Field        | Type           | Description                                  |
| ------------ | -------------- | -------------------------------------------- |
| id           | UUID / Integer | Primary key                                  |
| user_id      | UUID / Integer | Foreign key to User                          |
| name         | String         | Optional alternative name                    |
| email        | String         | Optional email                               |
| phone_number | String         | Optional phone                               |
| address      | String         | Delivery address                             |
| location     | JSON / Point   | Latitude/Longitude for delivery optimization |
| avatar_url   | String         | Optional                                     |
| created_at   | Timestamp      | Record creation time                         |
| updated_at   | Timestamp      | Last updated time                            |

---

## 3. Category

Represents product categories and subcategories.

| Field       | Type       | Description                          |
| ----------- | ---------- | ------------------------------------ |
| id          | UUID / Int | Primary key                          |
| name        | String     | Category name                        |
| description | String     | Optional                             |
| image_url   | String     | Optional                             |
| parent_id   | UUID / Int | Optional, to maintain sub-categories |
| created_at  | Timestamp  | Record creation time                 |
| updated_at  | Timestamp  | Last updated time                    |

---

## 4. Unit

Represents the unit of measurement for products (kg, liter, pack, etc.).

| Field        | Type       | Description           |
| ------------ | ---------- | --------------------- |
| id           | UUID / Int | Primary key           |
| name         | String     | Unit name (Kilogram)  |
| abbreviation | String     | Optional (kg, L, pcs) |
| created_at   | Timestamp  | Record creation time  |
| updated_at   | Timestamp  | Last updated time     |

---

## 5. Product

Represents grocery items listed in the app.

| Field          | Type       | Description                |
| -------------- | ---------- | -------------------------- |
| id             | UUID / Int | Primary key                |
| name           | String     | Product name               |
| category_id    | UUID / Int | Foreign key to Category    |
| price          | Decimal    | Price per unit             |
| quantity       | Decimal    | Numeric quantity (e.g., 3) |
| unit_id        | UUID / Int | Foreign key to Unit        |
| description    | String     | Optional                   |
| image_url      | String     | Optional                   |
| stock_quantity | Decimal    | Optional                   |
| is_active      | Boolean    | Product availability       |
| created_at     | Timestamp  | Record creation time       |
| updated_at     | Timestamp  | Last updated time          |

---

## 6. Cart

Represents the user’s shopping cart.

| Field       | Type       | Description                |
| ----------- | ---------- | -------------------------- |
| id          | UUID / Int | Primary key                |
| user_id     | UUID / Int | Foreign key to User        |
| status      | Enum       | active, ordered, cancelled |
| total_price | Decimal    | Calculated total           |
| created_at  | Timestamp  | Record creation time       |
| updated_at  | Timestamp  | Last updated time          |

---

## 7. CartItem

Represents products added to a cart.

| Field      | Type       | Description                 |
| ---------- | ---------- | --------------------------- |
| id         | UUID / Int | Primary key                 |
| cart_id    | UUID / Int | Foreign key to Cart         |
| product_id | UUID / Int | Foreign key to Product      |
| quantity   | Decimal    | Quantity added              |
| price      | Decimal    | Price at the time of adding |

---

## 8. Order

Represents a confirmed order placed by the user.

| Field                  | Type       | Description                                |
| ---------------------- | ---------- | ------------------------------------------ |
| id                     | UUID / Int | Primary key                                |
| user_id                | UUID / Int | Foreign key to User                        |
| cart_id                | UUID / Int | Foreign key to Cart                        |
| total_price            | Decimal    | Total order amount                         |
| status                 | Enum       | pending, in-progress, delivered, cancelled |
| delivery_address       | String     | Delivery address                           |
| expected_delivery_time | Timestamp  | Expected delivery time                     |
| payment_status         | Enum       | pending, completed, failed                 |
| created_at             | Timestamp  | Record creation time                       |
| updated_at             | Timestamp  | Last updated time                          |

---

## 9. Payment

Tracks payment details for orders.

| Field          | Type        | Description                |
| -------------- | ----------- | -------------------------- |
| id             | UUID / Int  | Primary key                |
| order_id       | UUID / Int  | Foreign key to Order       |
| amount         | Decimal     | Payment amount             |
| payment_method | Enum/String | cash, mobile banking, card |
| status         | Enum        | pending, completed, failed |
| transaction_id | String      | Optional                   |
| created_at     | Timestamp   | Record creation time       |

---

## 10. DeliveryMan

Represents delivery personnel.

| Field        | Type       | Description          |
| ------------ | ---------- | -------------------- |
| id           | UUID / Int | Primary key          |
| name         | String     | Full name            |
| phone_number | String     | Contact number       |
| email        | String     | Optional             |
| avatar_url   | String     | Optional             |
| is_active    | Boolean    | Availability status  |
| created_at   | Timestamp  | Record creation time |
| updated_at   | Timestamp  | Last updated time    |

---

## 11. Delivery

Tracks order delivery status.

| Field           | Type       | Description                 |
| --------------- | ---------- | --------------------------- |
| id              | UUID / Int | Primary key                 |
| order_id        | UUID / Int | Foreign key to Order        |
| delivery_man_id | UUID / Int | Foreign key to DeliveryMan  |
| status          | Enum       | assigned, picked, delivered |
| delivered_at    | Timestamp  | Optional delivery time      |
| created_at      | Timestamp  | Record creation time        |
| updated_at      | Timestamp  | Last updated time           |

---

## 12. Review / Feedback

Optional table for ratings per product.

| Field      | Type       | Description            |
| ---------- | ---------- | ---------------------- |
| id         | UUID / Int | Primary key            |
| user_id    | UUID / Int | Foreign key to User    |
| product_id | UUID / Int | Foreign key to Product |
| rating     | Integer    | 1-5                    |
| comment    | String     | Optional               |
| created_at | Timestamp  | Record creation time   |
