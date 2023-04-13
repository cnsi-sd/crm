@extends('layouts.horizontal', ["page_title"=> trans_choice('jobwatcher::jobs.job', 2)])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{ trans_choice('jobwatcher::jobs.failed_job', 2) }}
                <span class="badge bg-danger">{{ count($failed_jobs) }}</span>
                <div class="btn-group float-end" role="group">
                    <a href="{{ route('job_retry_failed_all') }}" class="btn btn-outline-primary">
                        <i class="uil-refresh"></i>
                    </a>
                    <a href="{{ route('job_delete_failed_all') }}" class="btn btn-outline-danger">
                        <i class="uil-trash"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr class="pb-0 mb-0">
                        <th class="text-center border-0 pb-0 mb-0">#</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.queue') }}</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.display_name') }}</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.status') }}</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.failed_at') }}</th>
                        <th class="text-center border-0 pb-0 mb-0"></th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse ($failed_jobs as $job)
                        <tr>
                            <td class="text-center">{{ $job->id }}</td>
                            <td>{{ $job->queue }}</td>
                            <td>{{ $job->payload()->displayName }}</td>
                            <td>
                                <span class="badge bg-{{ $job->status()->getCSSClass() }}">
                                    {{ $job->status()->value }}
                                </span>
                            </td>
                            <td>{{ $job->failed_at?->format('d/m/Y H:i:s') }}</td>
                            <td class="text-end">
                                <a
                                        data-bs-toggle="modal" data-bs-target="#modal_job_{{ $job->id }}"
                                        type="button" class="btn btn-outline-secondary">
                                    <i class="uil-eye"></i>
                                </a>
                                <div class="btn-group ms-1" role="group">
                                    <a
                                            href="{{ route('job_retry_failed', ['failed_job' => $job]) }}"
                                            type="button" class="btn btn-primary"
                                    >
                                        <i class="uil-refresh"></i>
                                    </a>
                                    <a
                                            href="{{ route('job_delete_failed', ['failed_job' => $job]) }}"
                                            type="button" class="btn btn-danger"
                                    >
                                        <i class="uil-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <em>{{ trans_choice('jobwatcher::jobs.no_failed_jobs', 2) }}</em>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                {{ trans_choice('jobwatcher::jobs.job', 2) }}
                <span class="badge bg-primary">{{ count($jobs) }}</span>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr class="pb-0 mb-0">
                        <th class="text-center border-0 pb-0 mb-0">#</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.queue') }}</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.display_name') }}</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.status') }}</th>
                        <th class="text-center border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.tries') }}</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.created_at') }}</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.available_at') }}</th>
                        <th class="border-0 pb-0 mb-0">{{ trans('jobwatcher::jobs.running_at') }}</th>
                        <th class="text-center border-0 pb-0 mb-0"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($jobs as $job)
                        <tr>
                            <td class="text-center">{{ $job->id }}</td>
                            <td>{{ $job->queue }}</td>
                            <td>{{ $job->payload()->displayName }}</td>
                            <td>
                                <span class="badge bg-{{ $job->status()->getCSSClass() }}">
                                    {{ $job->status()->value }}
                                </span>
                            </td>
                            <td class="text-center">{{ $job->attempts }} / {{ $job->payload()->maxTries ?? 1 }}</td>
                            <td>{{ $job->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $job->available_at?->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $job->running_at?->format('d/m/Y H:i:s') }}</td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    @if ($job->status() !== \Cnsi\JobWatcher\Enums\JobStatus::Running)
                                        <a
                                                href="{{ route('job_delete', ['job' => $job]) }}"
                                                type="button" class="btn btn-danger"
                                        >
                                            <i class="uil-trash"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <em>{{ trans('jobwatcher::jobs.no_jobs') }}</em>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($failed_jobs as $job)
        <div class="modal" id="modal_job_{{ $job->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">{{ trans('jobwatcher::jobs.job_detail') }}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-left">
                        @if($job->exception)
                            <h4 class="modal-title">{{ trans('jobwatcher::exception') }}</h4>
                            <pre>{{ $job->exception }}</pre>
                        @endif
                        <hr />
                        <h4 class="modal-title">{{ trans('jobwatcher::jobs.payload') }}</h4>
                        <pre>{{ \Symfony\Component\VarDumper\VarDumper::dump($job->payload()) }}</pre>
                        <hr />
                        <h4 class="modal-title">{{ trans('jobwatcher::jobs.command') }}</h4>
                        <pre>{{ \Symfony\Component\VarDumper\VarDumper::dump($job->command()) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
