--
# Crear un usuario 

 POST / registerUser

  Json ->  User / Token 201

--
# Logear un usuario 

 POST / loginUser

  Json ->  Token Session 201

--
# Cambiar contraseña de un usuario 

 POST / changeUserPasswd

  Json ->  User 201

--
# Crear cuenta bancaria de un usuario

 POST / createAccount

  Json ->  Bank Account 201

--
# Mostrar cuentas bancarias de un usuario

 GET / showAccount

  Json ->  User 201

--
# Deposito

 POST / depositAccount

  Json ->  User 201

--
# Retiro

 POST / withdrawAccount

  Json ->  User 201

--
# Pago

 POST / paymentAccount

  Json ->  User 201

--
# Pago

 POST / createLoan

  Json ->  User / Loan 201

--
# Pago

 POST / showLoanHistory

  Json ->  User 201

--
# Pago

 POST / showPaymentHistory

  Json ->  User 201
