-- Standardize delivery bill statuses and ensure not-delivered metadata columns exist.

UPDATE daily_delivery_bill
SET status = 'not_delivered'
WHERE status = 'cancelled';

ALTER TABLE daily_delivery_bill
ADD COLUMN IF NOT EXISTS reason TEXT NULL AFTER status,
ADD COLUMN IF NOT EXISTS remarks TEXT NULL AFTER reason;
