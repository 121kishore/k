(function () {
  document.addEventListener('click', function (event) {
    var option = event.target.closest('.skpq-option');
    if (!option) return;
    var question = option.closest('.skpq-question');
    var correct = option.dataset.correct === '1';
    question.querySelectorAll('.skpq-option').forEach(function (button) {
      button.disabled = true;
      if (button.dataset.correct === '1') button.classList.add('is-correct');
    });
    option.classList.add(correct ? 'is-correct' : 'is-wrong');
    question.querySelector('.skpq-feedback').textContent = (correct ? '✔ Correct. ' : '❌ Wrong. ') + (question.dataset.explanation || '');
  });
}());
