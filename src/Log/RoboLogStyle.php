<?php
namespace Robo\Log;

use Consolidation\Log\LogOutputStyler;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;

/**
 * Robo Log Styler.
 */
class RoboLogStyle extends LogOutputStyler
{
    const TASK_STYLE_SIMULATED = 'options=reverse;bold';

    public function __construct($labelStyles = [], $messageStyles = [])
    {
        parent::__construct($labelStyles, $messageStyles);

        $this->labelStyles += [
            RoboLogLevel::SIMULATED_ACTION => self::TASK_STYLE_SIMULATED,
        ];
        $this->messageStyles += [
            RoboLogLevel::SIMULATED_ACTION => '',
        ];
    }

    /**
     * Log style customization for Robo: replace the log level with
     * the task name.
     */
    protected function formatMessageByLevel($level, $message, $context)
    {
        $label = $level;
        if (array_key_exists('name', $context)) {
            $label = $context['name'];
        }
        return $this->formatMessage($label, $message, $context, $this->labelStyles[$level], $this->messageStyles[$level]);
    }

    /**
     * Log style customization for Robo: add the time indicator to the
     * end of the log message if it exists in the context.
     */
    protected function formatMessage($label, $message, $context, $taskNameStyle, $messageStyle = '')
    {
        $message = parent::formatMessage($label, $message, $context, $taskNameStyle, $messageStyle);

        if (array_key_exists('time', $context) && array_key_exists('timer-label', $context)) {
            $message .= ' ' . $context['timer-label'] . ' ' . $this->wrapFormatString($context['time'], 'fg=yellow');
        }

        return $message;
    }
}
