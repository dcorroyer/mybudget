# Plan d'améliorations MyBudget

Date : 19 juillet 2025

## 📊 1. Optimisation de l'historique des soldes (Backend - Symfony)

### Situation actuelle
- Chaque transaction crée une entrée dans `BalanceHistory` 
- Avec 1000 transactions/an → 1000 points en base de données
- Le graphique d'évolution nécessite uniquement l'état mensuel
- Performance dégradée avec beaucoup de transactions

### Solution proposée
**Agrégation mensuelle des soldes** au lieu d'un point par transaction

#### Architecture technique recommandée

1. **Nouvelle entité `MonthlyBalance`** :
```php
// src/Savings/Entity/MonthlyBalance.php
class MonthlyBalance
{
    private Account $account;
    private \DateTimeImmutable $month; // Premier jour du mois
    private float $balance; // Solde en fin de mois
    private \DateTimeImmutable $lastUpdated;
}
```

2. **Event-Driven avec Symfony Messenger** :
   - Créer un event `TransactionProcessedEvent`
   - Un handler qui calcule/met à jour le solde mensuel
   - Utiliser un `MessageBus` pour découpler la logique

3. **Service d'agrégation** :
```php
// src/Savings/Service/MonthlyBalanceService.php
class MonthlyBalanceService
{
    public function updateMonthlyBalance(Transaction $transaction): void
    {
        // 1. Identifier le mois de la transaction
        // 2. Recalculer le solde pour ce mois
        // 3. Mettre à jour MonthlyBalance
        // 4. Recalculer les mois suivants si nécessaire
    }
}
```

4. **Migration des données existantes** :
   - Script de migration pour convertir l'historique actuel
   - Conserver `BalanceHistory` temporairement pour rollback

### Points d'attention
- ⚠️ Gérer les modifications/suppressions de transactions passées
- ⚠️ Recalculer les mois suivants si une transaction du passé change
- ✅ Performance : de O(n) transactions à O(12) mois/an

---

## 💰 2. Gestion du solde initial des comptes (Backend - Symfony)

### Situation actuelle
- Pas de champ `initial_balance` dans l'entité `Account`
- Le solde démarre toujours à 0
- Impossible d'importer un compte existant avec un solde

### Solution proposée
**Transaction automatique de solde initial** à la création du compte

#### Architecture technique recommandée

1. **Modification du DTO** :
```php
// src/Savings/Dto/Payload/AccountPayload.php
class AccountPayload
{
    public string $name;
    public ?float $initialBalance = null;
    public ?AccountTypesEnum $type = AccountTypesEnum::SAVINGS;
}
```

2. **Event Subscriber pour la création** :
```php
// src/Savings/EventSubscriber/AccountSubscriber.php
class AccountSubscriber implements EventSubscriberInterface
{
    public function onAccountCreated(AccountCreatedEvent $event): void
    {
        if ($event->getInitialBalance() > 0) {
            // Créer une transaction de type CREDIT
            // Description: "Solde initial"
            // Date: date de création du compte
        }
    }
}
```

3. **Modification du service** :
```php
// src/Savings/Service/AccountService.php
public function create(AccountPayload $payload): AccountResponse
{
    // ... création du compte
    
    $this->eventDispatcher->dispatch(
        new AccountCreatedEvent($account, $payload->initialBalance)
    );
}
```

### Avantages
- ✅ Traçabilité complète (transaction visible)
- ✅ Cohérence avec le système existant
- ✅ Pas de modification du calcul de solde
- ✅ Réversible (suppression de la transaction)

---

## 🔧 3. Upgrade Symfony 7.1 → 7.3

### État actuel
- Version actuelle : Symfony 7.1
- Dernière version stable : Symfony 7.3.1

### Breaking changes mineurs
1. **Console** : Option `--silent` ajoutée (renommer si conflit)
2. **Cache** : `igbinary_serialize()` n'est plus utilisé automatiquement
3. **Webhook** : Type de retour modifié pour `RequestParserInterface::parse()`

### Plan de migration
1. Mettre à jour `composer.json` : `~7.1.0` → `~7.3.0`
2. Exécuter `composer update symfony/*`
3. Tester la suite de tests complète
4. Vérifier les dépréciations avec PHPStan

### Risque : **Très faible** ✅

---

## 🛠️ 4. Orval vs openapi-ts

### Recommandation : **Conserver Orval** ✅

#### Analyse comparative

| Critère | Orval (actuel) | openapi-ts |
|---------|----------------|------------|
| Génération hooks React Query | ✅ Automatique | ❌ Manuel |
| Organisation par tags | ✅ tags-split | ❌ Un seul fichier |
| Intégration axios | ✅ Native | ❌ À implémenter |
| Mocks automatiques | ✅ MSW + Faker | ❌ Non |
| Popularité | 346k/semaine | 1.4M/semaine |
| Taille du code généré | ❌ Important | ✅ Minimal |

### Points d'amélioration possibles
- Optimiser le script `download-api-and-generate.sh`
- Ajouter un cache pour éviter les régénérations inutiles
- Documenter les patterns d'utilisation dans l'équipe

---

## 🎨 5. Refonte du frontend React

### Objectifs
- Améliorer l'UX/UI basé sur le POC réussi
- Moderniser l'architecture React
- Implémenter les patterns appris récemment

### Approche recommandée

1. **Phase de préparation** :
   - Documenter le design system du POC
   - Identifier les composants réutilisables
   - Planifier la migration progressive

2. **Architecture moderne** :
   - Server Components (si Next.js)
   - Suspense boundaries
   - Error boundaries systématiques
   - Optimistic updates avec React Query

3. **Migration progressive** :
   - Commencer par les pages isolées
   - Migrer les composants partagés
   - Conserver l'API existante

### Technologies à considérer
- **UI** : Conserver Mantine ou migrer vers Radix UI + Tailwind
- **State** : Zustand pour le state client (si nécessaire)
- **Forms** : React Hook Form + Zod (déjà en place)
- **Charts** : Recharts (déjà utilisé)

---

## 📝 Guidelines pour l'implémentation

### Pour les tâches 1 et 2 (Backend Symfony)
**Mode mentorat activé** : Je guide l'architecture et les bonnes pratiques, tu codes.

#### Workflow suggéré
1. **Conception** : On discute de l'architecture ensemble
2. **Implementation** : Tu codes avec mes conseils
3. **Review** : Je t'aide à identifier les améliorations
4. **Tests** : On définit la stratégie de tests ensemble

#### Bonnes pratiques Symfony à respecter
- Domain-Driven Design (déjà en place)
- Event-driven pour le découplage
- Tests unitaires pour la logique métier
- Tests d'intégration pour les événements
- Utiliser les Value Objects quand pertinent

### Ordre de priorité suggéré
1. **Initial balance** (plus simple, bon échauffement)
2. **Optimisation historique** (plus complexe, impact performance)
3. **Upgrade Symfony** (rapide, peu risqué)
4. **Refonte frontend** (projet à part entière)

---

## 🚀 Prochaines étapes

1. **Validation** : Revoir ce plan ensemble
2. **Priorisation** : Définir l'ordre d'implémentation
3. **Estimation** : Évaluer l'effort pour chaque tâche
4. **Planning** : Créer un calendrier réaliste

Ce document est un point de départ pour nos discussions. N'hésite pas à me poser des questions sur l'architecture ou les choix techniques !