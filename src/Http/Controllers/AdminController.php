<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Events\Exports\Export;
use TypiCMS\Modules\Events\Http\Requests\FormRequest;
use TypiCMS\Modules\Events\Models\Event;

final class AdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('admin::events.index');
    }

    public function export(Request $request): BinaryFileResponse
    {
        $filename = date('Y-m-d').' '.config()->string('app.name').' events.xlsx';

        return Excel::download(new Export, $filename);
    }

    public function create(): View
    {
        $model = new Event;

        return view('admin::events.create', ['model' => $model]);
    }

    public function edit(Event $event): View
    {
        return view('admin::events.edit', ['model' => $event]);
    }

    public function store(FormRequest $request): RedirectResponse
    {
        $event = Event::query()->create($request->validated());

        return $this->redirect($request, $event)->withMessage(__('Item successfully created.'));
    }

    public function update(Event $event, FormRequest $request): RedirectResponse
    {
        $event->update($request->validated());

        return $this->redirect($request, $event)->withMessage(__('Item successfully updated.'));
    }
}
