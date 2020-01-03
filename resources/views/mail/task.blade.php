<p>Xin chào {{ $task->user->name }}</p>
<p>Bạn có công việc cần được nhắc nhở</p>
<p>Đối tượng: {{ $task->taskable->name }}</p>
<p>Tiêu đề: <a href="https://crm.adung.me/business/task/show/{{ $task->id }}">{{ $task->title }}</a></p>
<p>ADCRM</p>