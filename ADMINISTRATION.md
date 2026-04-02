# 📋 Guide d'Utilisation du Panneau d'Administration

## Accès

Cliquez sur le bouton **⚙️ Administration** dans le menu en haut à droite (visible uniquement si vous êtes connecté en tant qu'administrateur).

---

## 🤰 Partie 1: Informations sur la Grossesse

### Saisie des données:
- **Date de début**: Date de début de grossesse
- **Date d'accouchement**: Date prévue d'accouchement  
- **Sexe du bébé**: Non déterminé, Fille 👧, ou Garçon 👦

### Action:
Cliquez sur **✅ Enregistrer les informations** pour valider.

---

## 🛍️ Partie 2: Gestion des Plateformes de Vente

### Ajouter une nouvelle plateforme:

1. **Nom**: Ex: "Mon Magasin"
2. **URL de recherche**: 
   - Utilisez `{query}` pour le terme de recherche
   - Ex: `https://www.monmagasin.fr/search?q={query}`
3. **Regex** (optionnel):
   - Expression régulière pour détecter automatiquement le site
   - Ex: `/monmagasin\\.fr/i`
4. Cochez **Plateforme active** pour l'activer

### Gérer les plateformes existantes:

- **Éditer**: Cliquez sur ✏️ pour modifier les paramètres
- **Supprimer**: Cliquez sur 🗑️ pour supprimer (confirmation requise)
- **Statut**: Un badge indique si la plateforme est active ✅ ou inactive ❌

### Plateformes pré-configurées:
- Amazon
- Aubert
- Vertbaudet
- IKEA
- Cdiscount
- Bébé9

---

## 👤 Partie 3: Gestion des Utilisateurs

### Mettre à jour l'email administrateur:
1. Entrez la nouvelle adresse email
2. Cliquez sur **✅ Mettre à jour**

### Changer le mot de passe:
1. Entrez le nouveau mot de passe dans le champ "Mot de passe"
2. Laissez vide si vous ne voulez pas le modifier
3. Cliquez sur **✅ Mettre à jour**

---

## 📝 Intégration avec la génération de liens

Quand vous ajoutez un article, un bouton **🔗 Générer les liens** apparaît.

Ce bouton génère automatiquement les liens de recherche vers toutes les plateformes actives en utilisant:
- Le **nom de l'article** que vous avez saisi
- L'**URL de recherche** définie dans l'administration

**Exemple:**
- Article: "Transat bébé"
- Plateforme: Amazon
- Lien généré: `https://www.amazon.fr/s?k=Transat%20bébé`

---

## ⚙️ Configuration avancée

### Expressions régulières pour plateformes:

La colonne "Regex" permet de détecter automatiquement la plateforme quand vous saisissez une URL dans un article.

**Exemples:**
- Amazon: `/amazon\\.fr/i`
- Aubert: `/aubert\\.com/i`
- Cdiscount: `/cdiscount\\.com/i`

**Comment ça marche:**
1. Vous entrez une URL: `https://www.amazon.fr/product/xyz`
2. Le détecteur teste la regex
3. Si elle correspond, le label se remplit automatiquement avec "Amazon"

---

## 💾 Enregistrement des données

Toutes les modifications sont automatiquement sauvegardées en base de données.

Un message de confirmation s'affiche après chaque action ✅.
