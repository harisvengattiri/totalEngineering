This is for the purpose of developing completely for developers.


Category And SubCategory [Accounts] are implemented in the following places.
    - Journal[2][Debit and Credit]
    - Payment Voucher
    - Debit Note
    
    - Receipts
    - Refund
    - Miscellanious
    - Petty voucher
    
If we taking values, we should take values from
    - Opening Balance from subcategory
    - Journal[2][Debit and Credit]
    - Payment Voucher
    - Debit Note
    
Account Balance is Taking from
    - loads -> getAccountBalance
    - report -> ac_stmnt_cat
    - report -> chart of accounts
    - pv
    - debitnote
    
Bank is taking in
    - Receipts
    - Refund
    - Miscellanious
    - Petty voucher