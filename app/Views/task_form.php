<div class="container mt-4 text-light bg-dark p-4 rounded">
  <form id="taskForm" enctype="multipart/form-data">
    <div class="form-group">
      <label for="title">Название задачи</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <div class="form-group">
      <label for="description">Описание</label>
      <textarea class="form-control" id="description" name="description"></textarea>
    </div>

    <div class="form-group">
      <label for="due_date">Дата завершения</label>
      <input type="date" class="form-control" id="due_date" name="due_date">
    </div>

    <div class="form-group">
      <label for="labels">Метки (через запятую)</label>
      <input type="text" class="form-control" id="labels" name="labels">
    </div>

    <div class="form-group">
      <label for="subtasks">Подзадачи (по одной на строку)</label>
      <textarea class="form-control" id="subtasks" name="subtasks"></textarea>
    </div>

    <div class="form-group">
      <label for="attachment">Прикрепить файл</label>
      <input type="file" class="form-control-file" id="attachment" name="attachment">
    </div>

    <div class="form-group">
      <label>Координаты</label>
      <div class="d-flex gap-2">
        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Широта">
        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Долгота">
      </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Сохранить задачу</button>
  </form>
</div>

<script>
  $('#taskForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.set('labels', $('#labels').val().split(',').map(l => l.trim()));
    formData.set('subtasks', $('#subtasks').val().split('\n').map(s => s.trim()));

    $.ajax({
      url: '/api/task/create',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        alert('Задача сохранена');
        location.reload();
      },
      error: function(err) {
        alert('Ошибка при сохранении задачи');
        console.error(err);
      }
    });
  });
</script>
