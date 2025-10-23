<?php

namespace App\Controllers;

use App\Core\Auth\AuthManager;
use App\Core\Controller;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Support\Container;
use App\Core\Validation\Validator;
use App\Core\View\View;
use App\Models\Church;
use App\Models\ChurchRole;
use App\Models\ChurchRoleAssignment;
use App\Models\User;
use App\Services\RbacService;

class AdminController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly AuthManager $auth,
        private readonly RbacService $rbac,
        private readonly Validator $validator,
        private readonly Request $request,
        private readonly User $users,
        private readonly ChurchRole $roles,
        private readonly ChurchRoleAssignment $assignments,
        private readonly Church $church
    ) {
        parent::__construct($container, $view, $response);
    }

    public function index(): string
    {
        $this->authorize();
        $roles = $this->roles->all();
        $users = $this->users->all();
        $churches = $this->church->all();

        $recentAssignments = $this->assignments->recentWithDetails();

        return $this->renderLayout('Gestão Geral', 'admin.dashboard', compact('roles', 'users', 'churches', 'recentAssignments'));
    }

    public function assignRole(): Response
    {
        $this->authorize();
        $data = $this->request->all();
        $errors = $this->validator->validate($data, [
            'user_id' => 'required',
            'role_name' => 'required',
            'church_id' => 'required',
        ]);

        if ($errors) {
            return $this->response->setContent($this->renderLayout('Gestão Geral', 'admin.dashboard', [
                'errors' => $errors,
                'roles' => $this->roles->all(),
                'users' => $this->users->all(),
                'churches' => $this->church->all(),
                'recentAssignments' => $this->assignments->recentWithDetails(),
            ]));
        }

        $currentUser = $this->auth->user();
        $this->rbac->assignRole((int) $currentUser['id'], (int) $data['user_id'], $data['role_name'], (int) $data['church_id']);

        header('Location: /gerenciar');
        exit;
    }

    public function revokeRole(int $assignmentId): void
    {
        $this->authorize();
        $currentUser = $this->auth->user();
        $this->rbac->revokeRole((int) $currentUser['id'], $assignmentId);
        header('Location: /gerenciar');
        exit;
    }

    private function authorize(): void
    {
        $user = $this->auth->user();
        if (!$user || !$this->rbac->userHasRole((int) $user['id'], ['admin', 'Presidente'])) {
            header('Location: /login');
            exit;
        }
    }
}
