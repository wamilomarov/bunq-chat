create table users
(
    id         int auto_increment,
    username   varchar(255) not null,
    name       varchar(255) null,
    password   varchar(255) not null,
    token      varchar(255) null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint users_pk
        primary key (id),
    constraint users_pk2
        unique (username)
);

create table conversations
(
    id         int auto_increment,
    created_at timestamp null,
    updated_at timestamp null,
    constraint conversations_pk
        primary key (id)
);

create table conversations_users
(
    id              int auto_increment,
    user_id         int not null,
    conversation_id int not null,
    created_at      timestamp null,
    updated_at      timestamp null,
    constraint conversations_pk
        primary key (id),
    foreign key (user_id) REFERENCES users (id),
    foreign key (conversation_id) REFERENCES conversations (id)
);

create table messages
(
    id              int auto_increment,
    sender_id       int          not null,
    conversation_id int          not null,
    message         varchar(255) not null,
    created_at      timestamp null,
    updated_at      timestamp null,
    constraint conversations_pk
        primary key (id),
    foreign key (sender_id) REFERENCES users (id),
    foreign key (conversation_id) REFERENCES conversations (id)
);
