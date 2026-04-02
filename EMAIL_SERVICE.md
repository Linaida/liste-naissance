# 📧 Système d'envoi d'emails

## Configuration

Le système est configuré dans les fichiers suivants :

### 1. **Service EmailService** (`src/Service/EmailService.php`)
- `sendReservationConfirmation()` : Envoie un email de confirmation à la personne qui réserve
- `sendReservationCancellation()` : Envoie un email d'annulation

### 2. **Templates d'email** (`templates/emails/`)
- `reservation_confirmation.html.twig` : Email de confirmation
- `reservation_cancellation.html.twig` : Email d'annulation

### 3. **Configuration** (`config/services.yaml`, `.env`)
- Paramètres : `MAILER_FROM_EMAIL` et `MAILER_FROM_NAME`
- Mailer DSN : `MAILER_DSN=null://null` (mode développement - logs dans la console)

---

## Flux de réservation

### Étape 1️⃣ : Création de réservation
```
POST /api/reservation/create
```

Données requises :
```json
{
  "articleId": 1,
  "name": "Jean Dupont",
  "email": "jean@example.com",
  "message": "Message optionnel"
}
```

### Étape 2️⃣ : Actions automatiques
1. ✅ Réservation créée en base de données
2. 📧 Email de confirmation envoyé automatiquement
3. 🔔 La personne reçoit la confirmation avec :
   - Nom de l'article
   - Numéro de réservation (#ID)
   - Prix et catégorie
   - Date/heure
   - Son message (s'il y en a un)

### Étape 3️⃣ : Annulation de réservation
```
POST /api/reservation/cancel
```

Données requises :
```json
{
  "articleId": 1,
  "email": "jean@example.com"
}
```

Actions :
1. 📧 Email d'annulation envoyé avant suppression
2. ❌ Réservation supprimée
3. 🔓 Article marqué comme "Disponible"

---

## Configuration en production

Pour passer en production, modifiez le `MAILER_DSN` dans votre `.env.local` :

### **Gmail SMTP** :
```
MAILER_DSN=gmail+smtp://username:password@smtp.gmail.com
```

### **SendGrid** :
```
MAILER_DSN=sendgrid+api://SG.YOUR_API_KEY@default
```

### **AWS SES** :
```
MAILER_DSN=ses+smtp://your_key:your_secret@email-smtp.region.amazonaws.com
```

### **Postmark** :
```
MAILER_DSN=postmark+api://YOUR_API_TOKEN@default
```

### **Mailgun** :
```
MAILER_DSN=mailgun+https://YOUR_DOMAIN%40mg.example.com:YOUR_API_KEY@default
```

---

## Variables d'environnement

Mettez à jour dans `.env` ou `.env.local` :

```env
MAILER_DSN=your-mailer-dsn
MAILER_FROM_EMAIL=noreply@yoursite.com
MAILER_FROM_NAME="Votre Nom"
```

---

## Gestion des erreurs

Si l'envoi d'email échoue :
- ❌ L'erreur est loggée mais ne bloque pas la création de la réservation
- ✅ La réservation est créée même si l'email échoue
- 👤 L'utilisateur peut toujours voir sa réservation

Cela garantit que le système reste fonctionnel même en cas de problème d'email.

---

## Tester en développement

En mode développement (`MAILER_DSN=null://null`), les emails sont affichés dans la console Symfony :

```bash
docker compose logs app
```

Vous verrez un message comme :
```
[Sending email] To: jean@example.com
Title: Confirmation de réservation - Mon Article
```

---

## Personnalisation des templates

Les templates se trouvent dans `templates/emails/` et peuvent être personnalisés :
- Ajouter un logo
- Modifier les couleurs
- Ajouter des liens vers votre site
- Personnaliser le message d'accueil

Utilisez les variables Twig disponibles :
- `{{ reservation.name }}`
- `{{ reservation.email }}`
- `{{ reservation.message }}`
- `{{ reservation.createdAt }}`
- `{{ article.name }}`
- `{{ article.price }}`
- `{{ article.category.label() }}`
