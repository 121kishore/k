(function () {
  function refreshQuestionPreview() {
    var question = document.querySelector('.skpq-preview-question');
    var previewQuestion = document.querySelector('[data-skpq-preview-question]');
    var previewOptions = document.querySelector('[data-skpq-preview-options]');
    if (!question || !previewQuestion || !previewOptions) return;
    previewQuestion.textContent = question.value || 'Your question preview appears here.';
    previewOptions.innerHTML = '';
    document.querySelectorAll('.skpq-preview-option').forEach(function (option) {
      if (!option.value) return;
      var line = document.createElement('p');
      line.textContent = '○ ' + option.value;
      previewOptions.appendChild(line);
    });
  }

  document.documentElement.classList.add('skpq-admin-ready');
  document.addEventListener('input', refreshQuestionPreview);
  document.addEventListener('DOMContentLoaded', refreshQuestionPreview);
}());
