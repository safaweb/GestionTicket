<?php

return [
    /**
     * Default route to see the UML diagram.
     */
    'route' => '/uml',

    /**
     * You can turn on or off the indexing of specific types
     * of classes. By default, LTU processes only controllers
     * and models.
     */
    'casts'         => false,
    'channels'      => false,
    'commands'      => false,
    'components'    => false,
    'controllers'   => false,
    'events'        => false,
    'exceptions'    => false,
    'jobs'          => false,
    'listeners'     => false,
    'mails'         => false,
    'middlewares'   => false,
    'models'        => true,
    'notifications' => false,
    'observers'     => false,
    'policies'      => false,
    'providers'     => false,
    'requests'      => false,
    'resources'     => false,
    'rules'         => false,

    /**
     * You can define specific nomnoml styling.
     * For more information: https://github.com/skanaar/nomnoml
     */
    'style' => [
        'background' => '#071013',
        'stroke'     => '#EBEBEB',
        'arrowSize'  => 1,
        'bendSize'   => 0.3,
        'direction'  => 'down',
        'gutter'     => 5,
        'edgeMargin' => 0,
        'gravity'    => 1,
        'edges'      => 'rounded',
        'fill'       => '#3A6EA5',
        'fillArrows' => false,
        'font'       => 'Calibri',
        'fontSize'   => 12,
        'leading'    => 1.25,
        'lineWidth'  => 3,
        'padding'    => 8,
        'spacing'    => 50,
        'title'      => 'Filename',
        'zoom'       => 1,
        'acyclicer'  => 'greedy',
        'ranker'     => 'longest-path'
    ],

    /**
     * Specific files can be excluded if need be.
     * By default, all default Laravel classes are ignored.
     */
    'excludeFiles' => [
        'Http/Kernel.php',
        'Console/Kernel.php',
        'Exceptions/Handler.php',
        'Http/Controllers/Controller.php',
        'Http/Middleware/Authenticate.php',
        'Http/Middleware/EncryptCookies.php',
        'Http/Middleware/PreventRequestsDuringMaintenance.php',
        'Http/Middleware/RedirectIfAuthenticated.php',
        'Http/Middleware/TrimStrings.php',
        'Http/Middleware/TrustHosts.php',
        'Http/Middleware/TrustProxies.php',
        'Http/Middleware/VerifyCsrfToken.php',
        'Http/Middleware/ValidateSignature.php',
        'Providers/AppServiceProvider.php',
        'Providers/AuthServiceProvider.php',
        'Providers/BroadcastServiceProvider.php',
        'Providers/EventServiceProvider.php',
        'Providers/RouteServiceProvider.php',
        'Filament/Resources/ProblemCategoryResource.php',
        'Filament/Resources/StatutDuTicketResource.php',
        'Filament/Resources/TicketResource.php',
        'Filament/Resources/ProjetResource.php',
        'Filament/Resources/UserResource.php',
        'Filament/Resources/ProblemCategoryResource/RelationManagers/TicketsRelationManager.php',
        'Filament/Resources/TicketResource/RelationManagers/CommentairesRelationManager.php',
        'Filament/Resources/StatutDuTicketResource/RelationManagers/TicketsRelationManager.php',
        'Filament/Resources/UserResource/RelationManagers/TicketsRelationManager.php',
        'Policies/CommentairePolicy.php',
        'Policies/PermissionPolicy.php',
        'Policies/PriorityPolicy.php',
        'Policies/ProblemCategoryPolicy.php',
        'Policies/RolePolicy.php',
        'Policies/TicketPolicy.php',
        'Policies/StatutDuTicketPolicy.php',
        'Policies/ProjetPolicy.php',
        'Policies/UserPolicy.php',
        'Models/Role.php',
        'Http/Controllers/Auth/SocialiteController.php',
        'Filament/Pages/Dashboard.php',
        'Filament/Pages/MyProfile.php',
        'Filament/Resources/ProblemCategoryResource/Pages/CreateProblemCategory.php',
        'Filament/Resources/ProblemCategoryResource/Pages/EditProblemCategory.php',
        'Filament/Resources/ProblemCategoryResource/Pages/ListProblemCategories.php',
        'Filament/Resources/ProblemCategoryResource/Pages/ViewProblemCategory.php',
        'Filament/Resources/TicketResource/Pages/CreateTicket.php',
        'Filament/Resources/TicketResource/Pages/EditTicket.php',
        'Filament/Resources/TicketResource/Pages/ListTickets.php',
        'Filament/Resources/TicketResource/Pages/ViewTicket.php',
        'Filament/Resources/StatutDuTicketResource/Pages/CreateStatutDuTicket.php',
        'Filament/Resources/StatutDuTicketResource/Pages/EditStatutDuTicket.php',
        'Filament/Resources/StatutDuTicketResource/Pages/ListStatutsDesTickets.php',
        'Filament/Resources/StatutDuTicketResource/Pages/ViewStatutDuTicket.php',
        'Filament/Resources/ProjetResource/Pages/CreateProjet.php',
        'Filament/Resources/ProjetResource/Pages/EditProjet.php',
        'Filament/Resources/ProjetResource/Pages/ListProjets.php',
        'Filament/Resources/ProjetResource/Pages/ViewProjet.php',
        'Filament/Resources/UserResource/Pages/CreateUser.php',
        'Filament/Resources/UserResource/Pages/EditUser.php',
        'Filament/Resources/UserResource/Pages/ListUsers.php',
        'Filament/Resources/UserResource/Pages/ViewUser.php',
        'Filament/Resources/ProjetResource/RelationManagers/UsersRelationManager.php',
        'Filament/Resources/ProjetResource/RelationManagers/ProblemCategoriesRelationManager.php',
        'Filament/Resources/UserResource/RelationManagers/RolesRelationManager.php',
    ],

    /**
     * In case you changed any of the default directories
     * for different classes, please amend below.
     */
    'directories' => [

        'models'        => 'Models/',
        'notifications' => 'Notifications/',

    ],
];
