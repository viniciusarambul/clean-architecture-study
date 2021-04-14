create table users(
    id varchar (36) UNIQUE PRIMARY KEY,
    name varchar (50),
    document varchar (20),
    email varchar (50) UNIQUE,
    password varchar (50)
);
create table accounts(
    id varchar (36) UNIQUE PRIMARY KEY,
    user_id varchar (36),
    number int UNIQUE,
    type varchar (50),
    CONSTRAINT type_enum check (type IN ('person', 'merchant'))
);
create table transactions(
    id varchar (36) UNIQUE PRIMARY KEY,
    payer_id varchar (36),
    payee_id varchar (36),
    amount decimal (10,2)
);
insert into users (id, name, document, email, password) values ('fb486204-9bef-11eb-a8b3-0242ac130003', 'vinicius', '08749490923', 'vinicius.vieira@hotmail.com', 'password');
insert into users (id, name, document, email, password) values ('183a0610-9bf0-11eb-a8b3-0242ac130003', 'teste', '08749490822', 'teste@teste.com', 'password');
insert into accounts (id, user_id, number, type) values ('7491ef7c-9bf0-11eb-a8b3-0242ac130003', 'fb486204-9bef-11eb-a8b3-0242ac130003', 109, 'person');
insert into accounts (id, user_id, number, type) values ('a9650144-9bf0-11eb-a8b3-0242ac130003', '183a0610-9bf0-11eb-a8b3-0242ac130003', 110, 'person');
insert into transactions (id, payer_id, payee_id, amount) values ('4a9bc71a-9bf5-11eb-a8b3-0242ac130003', '7491ef7c-9bf0-11eb-a8b3-0242ac130003','a9650144-9bf0-11eb-a8b3-0242ac130003', 10000.00)

