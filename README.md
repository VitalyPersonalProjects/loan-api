# Loan API (Yii2 + PostgreSQL + Docker)

API для подачи и обработки заявок на займ.

**Стек технологий:**
- PHP 8 + Yii2 Framework
- PostgreSQL
- Nginx
- Docker Compose

---

## **Эндпоинты**

### 1. Подача заявки
**POST /requests**  
Тело запроса (JSON):
```json
{
  "user_id": 1,
  "amount": 3000,
  "term": 30
}
```

**Ответ:**
- Успех (HTTP 201):
```json
{
  "result": true,
  "id": 1
}
```
- Ошибка (HTTP 400):
```json
{
  "result": false,
  "error": "описание ошибки"
}
```

---

### 2. Обработка заявок
**GET /processor?delay=5**  
- `delay` — задержка обработки каждой заявки (секунды)  
- 10% вероятность одобрения заявки  
- У одного пользователя не может быть более одной одобренной заявки  

**Ответ (HTTP 200):**
```json
{
  "result": true
}
```

---

## **Запуск проекта**

1. Склонировать репозиторий:

```bash
git clone https://github.com/<username>/loan-api.git
cd loan-api
```

2. Запустить Docker Compose:

```bash
sudo docker compose up -d
```

3. Доступ к API:

```
http://localhost:80
```

---

## **Конфигурация базы данных**

- host: `db` (контейнер)
- port: `5432`
- dbname: `loans`
- username: `user`
- password: `password`

---

## **Применение миграций**

```bash
sudo docker compose exec app php yii migrate
```

---

## **Пример использования cURL**

### Создание заявки:
```bash
curl -X POST http://localhost:80/requests \
-H "Content-Type: application/json" \
-d '{"user_id":1,"amount":3000,"term":30}'
```

### Обработка заявок:
```bash
curl http://localhost:80/processor?delay=5
```

---
