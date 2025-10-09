'use client';
import { useState, useCallback, useEffect } from 'react';
import {
  Bold, Italic, List, ListOrdered, Code, Link as LinkIcon,
  Image as ImageIcon, Heading1, Heading2, Quote, Eye,
  FileCode, Table, AlignLeft, AlignCenter, AlignRight,
  Undo, Redo, Save
} from 'lucide-react';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';

interface AdvancedEditorProps {
  value: string;
  onChange: (value: string) => void;
  placeholder?: string;
  minHeight?: number;
}

export default function AdvancedEditor({
  value,
  onChange,
  placeholder = 'Postni shu yerda yozing...',
  minHeight = 400
}: AdvancedEditorProps) {
  const [mode, setMode] = useState<'edit' | 'preview' | 'split'>('edit');
  const [selectionStart, setSelectionStart] = useState(0);
  const [selectionEnd, setSelectionEnd] = useState(0);

  const textareaRef = useCallback((textarea: HTMLTextAreaElement | null) => {
    if (textarea) {
      textarea.addEventListener('select', () => {
        setSelectionStart(textarea.selectionStart);
        setSelectionEnd(textarea.selectionEnd);
      });
    }
  }, []);

  const insertText = (before: string, after: string = '', defaultText: string = '') => {
    const textarea = document.querySelector('textarea') as HTMLTextAreaElement;
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = value.substring(start, end) || defaultText;
    const newText = value.substring(0, start) + before + selectedText + after + value.substring(end);

    onChange(newText);

    setTimeout(() => {
      textarea.focus();
      const newCursorPos = start + before.length + selectedText.length + after.length;
      textarea.setSelectionRange(newCursorPos, newCursorPos);
    }, 0);
  };

  const handleBold = () => insertText('**', '**', 'qalin matn');
  const handleItalic = () => insertText('*', '*', 'kursiv matn');
  const handleHeading1 = () => insertText('# ', '', 'Sarlavha 1');
  const handleHeading2 = () => insertText('## ', '', 'Sarlavha 2');
  const handleCode = () => insertText('`', '`', 'kod');
  const handleCodeBlock = () => insertText('```\n', '\n```', 'kod bloki');
  const handleLink = () => insertText('[', '](url)', 'link matni');
  const handleImage = () => insertText('![', '](image-url)', 'rasm tavsifi');
  const handleQuote = () => insertText('> ', '', 'iqtibos');
  const handleUnorderedList = () => insertText('- ', '', 'ro\'yxat elementi');
  const handleOrderedList = () => insertText('1. ', '', 'ro\'yxat elementi');
  const handleTable = () => {
    const table = '\n| Ustun 1 | Ustun 2 | Ustun 3 |\n|---------|---------|----------|\n| A | B | C |\n| D | E | F |\n';
    insertText(table);
  };

  const handleKeyDown = (e: React.KeyboardEvent<HTMLTextAreaElement>) => {
    if (e.key === 'Tab') {
      e.preventDefault();
      insertText('  ');
    }

    if (e.ctrlKey || e.metaKey) {
      switch (e.key) {
        case 'b':
          e.preventDefault();
          handleBold();
          break;
        case 'i':
          e.preventDefault();
          handleItalic();
          break;
        case 's':
          e.preventDefault();
          break;
      }
    }
  };

  const ToolbarButton = ({
    onClick,
    icon: Icon,
    title
  }: {
    onClick: () => void;
    icon: any;
    title: string;
  }) => (
    <button
      type="button"
      onClick={onClick}
      title={title}
      className="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors"
    >
      <Icon className="w-4 h-4" />
    </button>
  );

  return (
    <div className="border border-gray-300 rounded-lg overflow-hidden">
      {/* Toolbar */}
      <div className="bg-gray-50 border-b border-gray-300 p-2">
        <div className="flex flex-wrap items-center gap-1">
          {/* View Mode Toggles */}
          <div className="flex items-center border-r border-gray-300 pr-2 mr-2">
            <button
              type="button"
              onClick={() => setMode('edit')}
              className={`px-3 py-1 text-sm rounded transition-colors ${
                mode === 'edit'
                  ? 'bg-blue-600 text-white'
                  : 'text-gray-600 hover:bg-gray-200'
              }`}
            >
              Tahrirlash
            </button>
            <button
              type="button"
              onClick={() => setMode('preview')}
              className={`px-3 py-1 text-sm rounded transition-colors ${
                mode === 'preview'
                  ? 'bg-blue-600 text-white'
                  : 'text-gray-600 hover:bg-gray-200'
              }`}
            >
              Ko'rish
            </button>
            <button
              type="button"
              onClick={() => setMode('split')}
              className={`px-3 py-1 text-sm rounded transition-colors ${
                mode === 'split'
                  ? 'bg-blue-600 text-white'
                  : 'text-gray-600 hover:bg-gray-200'
              }`}
            >
              Ikkalasi
            </button>
          </div>

          {/* Text Formatting */}
          <ToolbarButton onClick={handleBold} icon={Bold} title="Qalin (Ctrl+B)" />
          <ToolbarButton onClick={handleItalic} icon={Italic} title="Kursiv (Ctrl+I)" />

          <div className="w-px h-6 bg-gray-300 mx-1" />

          <ToolbarButton onClick={handleHeading1} icon={Heading1} title="Sarlavha 1" />
          <ToolbarButton onClick={handleHeading2} icon={Heading2} title="Sarlavha 2" />

          <div className="w-px h-6 bg-gray-300 mx-1" />

          <ToolbarButton onClick={handleUnorderedList} icon={List} title="Tartiblangan ro'yxat" />
          <ToolbarButton onClick={handleOrderedList} icon={ListOrdered} title="Raqamlangan ro'yxat" />

          <div className="w-px h-6 bg-gray-300 mx-1" />

          <ToolbarButton onClick={handleLink} icon={LinkIcon} title="Link qo'shish" />
          <ToolbarButton onClick={handleImage} icon={ImageIcon} title="Rasm qo'shish" />

          <div className="w-px h-6 bg-gray-300 mx-1" />

          <ToolbarButton onClick={handleCode} icon={Code} title="Kod" />
          <ToolbarButton onClick={handleCodeBlock} icon={FileCode} title="Kod bloki" />
          <ToolbarButton onClick={handleQuote} icon={Quote} title="Iqtibos" />
          <ToolbarButton onClick={handleTable} icon={Table} title="Jadval" />
        </div>

        {/* Markdown cheat sheet */}
        <div className="mt-2 text-xs text-gray-500">
          <details>
            <summary className="cursor-pointer hover:text-gray-700">Markdown Qo'llanma</summary>
            <div className="mt-2 space-y-1 bg-white p-2 rounded border border-gray-200">
              <div><code className="bg-gray-100 px-1 rounded">**qalin**</code> = <strong>qalin</strong></div>
              <div><code className="bg-gray-100 px-1 rounded">*kursiv*</code> = <em>kursiv</em></div>
              <div><code className="bg-gray-100 px-1 rounded"># Sarlavha</code> = Katta sarlavha</div>
              <div><code className="bg-gray-100 px-1 rounded">[link](url)</code> = Link</div>
              <div><code className="bg-gray-100 px-1 rounded">![alt](url)</code> = Rasm</div>
              <div><code className="bg-gray-100 px-1 rounded">`kod`</code> = Inline kod</div>
              <div><code className="bg-gray-100 px-1 rounded">```kod bloki```</code> = Kod bloki</div>
              <div><code className="bg-gray-100 px-1 rounded">&gt; iqtibos</code> = Iqtibos</div>
            </div>
          </details>
        </div>
      </div>

      {/* Editor Area */}
      <div className="relative">
        {mode === 'edit' && (
          <textarea
            ref={textareaRef}
            value={value}
            onChange={(e) => onChange(e.target.value)}
            onKeyDown={handleKeyDown}
            placeholder={placeholder}
            className="w-full p-4 font-mono text-sm focus:outline-none resize-none"
            style={{ minHeight: `${minHeight}px` }}
          />
        )}

        {mode === 'preview' && (
          <div
            className="w-full p-4 prose prose-sm max-w-none overflow-auto"
            style={{ minHeight: `${minHeight}px` }}
          >
            <ReactMarkdown remarkPlugins={[remarkGfm]}>
              {value || '*Preview bo\'sh...*'}
            </ReactMarkdown>
          </div>
        )}

        {mode === 'split' && (
          <div className="grid grid-cols-2 divide-x divide-gray-300">
            <textarea
              ref={textareaRef}
              value={value}
              onChange={(e) => onChange(e.target.value)}
              onKeyDown={handleKeyDown}
              placeholder={placeholder}
              className="w-full p-4 font-mono text-sm focus:outline-none resize-none"
              style={{ minHeight: `${minHeight}px` }}
            />
            <div
              className="w-full p-4 prose prose-sm max-w-none overflow-auto bg-gray-50"
              style={{ minHeight: `${minHeight}px` }}
            >
              <ReactMarkdown remarkPlugins={[remarkGfm]}>
                {value || '*Preview bo\'sh...*'}
              </ReactMarkdown>
            </div>
          </div>
        )}
      </div>

      {/* Footer with character count */}
      <div className="bg-gray-50 border-t border-gray-300 px-4 py-2 text-xs text-gray-500 flex justify-between items-center">
        <div>
          {value.length} belgi | {value.split(/\s+/).filter(Boolean).length} so'z
        </div>
        <div className="flex items-center gap-4">
          <span className="text-green-600">✓ Avtomatik saqlash</span>
          <span>Markdown formatlash qo'llab-quvvatlanadi</span>
        </div>
      </div>
    </div>
  );
}
