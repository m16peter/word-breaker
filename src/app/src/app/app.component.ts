import { Component, OnInit, ViewChild, HostListener, ViewChildren } from '@angular/core';
import { AppService } from "./app.service";
import { environment } from './../environments/environment';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})

export class AppComponent implements OnInit
{
  private DEBUG = !environment.production;
  public loading: boolean;
  public list: any;

  @ViewChildren('item') items;
  @ViewChild('translate') translate;

  constructor(private http: AppService)
  {
    console.log('DEBUG', this.DEBUG);

    this.loading = true;
    this.list =
    {
      'selected': -1,
      'lines': [],
      'extra': [],
      'type': 'target',
      'selection':
      {
        'start': 0,
        'end': 0,
        'len': 0
      }
    };
  }

  ngOnInit()
  {
    let URL = this.DEBUG ? 'api/api/getJson' : 'api/getJson';

    this.http.get(URL).subscribe((response) =>
    {
      this.DEBUG ? console.log('api/getJson:', response) : this.noop();

      response = JSON.parse(response.data.json_string);

      this.list.lines = (response.data === '') ? [ {'extraBefore': '', 'extraAfter': '', 'source': '', 'target': ''} ] : response.data;
      this.list.lines.forEach(() => this.list.extra.push({ 'selected': false, 'updated': false, 'loading': false }));

      this.loading = false;

      setTimeout(() =>
      {
        if (this.list.lines.length > 0)
        {
          this.selectLine(0, 'source');
          this.items._results[this.list.selected].nativeElement.children[1].children[1].focus();
        }
        else
        {
          this.addEmptyLine();
          this.selectLine(0, 'source');
          this.items._results[this.list.selected].nativeElement.children[1].children[1].focus();
        }
      }, 500);
    });
  }

  public imgSrc(icon: string): string {
    return ('assets/img/' + icon);
  }



  /** SELECT / DESELECT **/

  public select(ev: any, i: number, type: string): void {
    this.selectLine(i, type);
    this.updateSelection(ev);
    if (this.list.selected === this.list.lines.length - 1) {
      this.addEmptyLine();
    }
  }

  public isSelectedLine(index: number): boolean {
    return (this.list.extra[index].selected);
  }

  private selectLine(i: number, type: string = ''): void {
    this.deselectAll();

    if (this.list.selected !== i) {
      this.scrollTo(this.items._results[i].nativeElement.offsetTop);
    }
    this.list.selected = i;
    this.list.extra[this.list.selected].selected = true;
    this.list.type = (type !== '') ? type : this.list.type;
  }



  /** BREAK **/

  public breakLine(): void
  {
    if (this.list.type === 'source' && this.list.selection.end !== 0 && this.list.selection.start !== this.list.selection.len) {
      const data = this.list.lines[this.list.selected].source;
      const extraBefore = this.list.lines[this.list.selected]['extraBefore'];
      const dataBefore = data.slice(0, this.list.selection.start);
      const selection = data.slice(this.list.selection.start, this.list.selection.end);
      const dataAfter = data.slice(this.list.selection.end, this.list.selection.len);
      const extraAfter = this.list.lines[this.list.selected]['extraAfter'];
      this.breakWord(extraBefore, dataBefore, selection, dataAfter, extraAfter);
      this.focusTarget();
    }
  }

  public canBreak(): boolean {
    return (this.list.type === 'source');
  }

  private breakWord(extraBefore, dataBefore, selection, dataAfter, extraAfter, update = true): void {
    if (dataBefore === '') {
      if (dataAfter === '') {
        this.list.lines[this.list.selected]['extraBefore'] = extraBefore + selection + extraAfter;
        this.list.lines[this.list.selected].source = '';
        if (update) {
          this.items._results[this.list.selected].nativeElement.children[1].children[1].value = '';
        }
      }
      else {
        this.list.lines[this.list.selected]['extraBefore'] = extraBefore + selection;
        this.list.lines[this.list.selected].source = dataAfter;
        if (update) {
          this.items._results[this.list.selected].nativeElement.children[1].children[1].value = dataAfter;
        }
      }
    }
    else {
      if (dataAfter === '') {
        this.list.lines[this.list.selected].source = dataBefore;
        if (update) {
          this.items._results[this.list.selected].nativeElement.children[1].children[1].value = dataBefore;
        }
        this.list.lines[this.list.selected]['extraAfter'] = selection + extraAfter;
      }
      else {
        this.list.lines[this.list.selected].source = dataBefore;
        if (update) {
          this.items._results[this.list.selected].nativeElement.children[1].children[1].value = dataBefore;
        }
        this.list.lines[this.list.selected]['extraAfter'] = selection;

        this.list.extra.splice(
          this.list.selected + 1,
          0,
          {
            'selected': false,
            'updated': false,
            'loading': false
          }
        );
        this.list.lines.splice(
          this.list.selected + 1,
          0,
          {
            'extraBefore': '',
            'extraAfter': extraAfter,
            'source': dataAfter,
            'target': ''
          }
        );
      }
    }
  }



  /** TRANSLATE **/

  public translateSelected(): void {
    if (this.list.selected !== -1) {
      if (this.list.selected === -2) {
        const n = this.list.lines.length;

        for (let i = 0; i < n; i++) {
          if (this.list.extra[i].selected) {
            if (this.list.lines[i].target === '') {
              this.translateLine(i);
            }
            else {
              this.updateLine(i, false);
            }
          }
        }
      }
      else {
        if (this.list.lines[this.list.selected].target === '') {
          this.translateLine(this.list.selected);
        }
        else {
          this.updateLine(this.list.selected);
        }
      }
    }
  }

  private translateLine(i: number): void {
    if (this.list.lines[i].source.trim() === '') {
      return;
    }
    this.items._results[i].nativeElement.children[2].children[1].value = '';
    this.list.lines[i].target = '';
    this.list.extra[i].loading = true;

    this.http.post('/translate', JSON.stringify({ 'data': this.list.lines[i].source })).then(
      (res) => {
        // console.log('translated: "' + this.list.lines[i].source + '" -> "' + res.data + '"');
        this.items._results[i].nativeElement.children[2].children[1].value = res.data;
        this.list.lines[i].target = res.data;
        this.list.extra[i].loading = false;
      },
      (err) => {
        // console.log('translate error: ', err);
        this.list.extra[i].loading = false;
      }
    );
  }

  private updateLine(i: number, changeFocus: boolean = true): void {
    if (this.list.lines[i].source.trim() === '') {
      return;
    }
    else if (changeFocus) {
      this.focusNext('source');
    }

    const data = {
      'data': {
        'source': this.list.lines[i].source,
        'target': this.list.lines[i].target
      }
    };

    this.http.post('/update', JSON.stringify(data)).then(
      (res) => {
        // console.log('update: ' + res);
      },
      (err) => {
        // console.log('update error: ', err);
      }
    );
  }

  public canTranslate(): boolean {
    if (this.list.selected === -1 || this.list.type === 'source') {
      return (false);
    }
    return (true);
  }

  public sourceIsEmpty(): boolean {
    if (this.list.selected >= 0) {
      if (this.list.lines[this.list.selected].source.trim() === '') {
        return (true);
      }
    }
    return (false);
  }

  public targetIsEmpty(): boolean {
    if (this.list.selected >= 0) {
      if (this.list.lines[this.list.selected].target === '') {
        return (true);
      }
    }
    return (false);
  }



  /** INPUT **/

  public focus(i: number, type: string): void {
    this.selectLine(i, type);
  }

  public updateContent(ev: any): void {
    // console.log('update-line', this.list.selected);
    this.list.lines[this.list.selected][this.list.type] = ev.target.value;
    this.items._results[this.list.selected].nativeElement.children[(this.list.type === 'target') ? (2) : (1)].children[1].value = ev.target.value;
    this.updateSelection(ev);

    if (this.list.selected === this.list.lines.length - 1) {
      this.addEmptyLine();
    }
  }

  public onKeyPress(ev: any): void {
    if (ev.ctrlKey) {
      switch (ev.code) {
        case 'Enter':
          {
            switch (this.list.type) {
              case 'source':
                {
                  this.updateSelection(ev);

                  if (this.canBreak() && !this.selectionIsInvalid()) {
                    this.breakLine();
                  }
                  else if (this.canBreak() && this.selectionIsInvalid()) {
                    this.focusTarget();
                  }
                  break;
                }
              case 'target':
                {
                  if (this.canTranslate() && !this.sourceIsEmpty()) {
                    this.translateSelected();
                  }
                  else if (this.canTranslate() && this.sourceIsEmpty()) {
                    this.focusSource();
                  }
                  break;
                }
              default: break;
            }
            break;
          }
        case 'Space':
          {
            this.focusNext(this.list.type);
            break;
          }
        default: break;
      }
    }
  }

  private updateSelection(ev: any): void {
    if (this.list.type === 'source') {
      // console.log('selection:', ev.target.selectionStart, ':', ev.target.selectionEnd);
      this.list.selection.start = ev.target.selectionStart;
      this.list.selection.end = ev.target.selectionEnd;
      this.list.selection.len = ev.target.textLength;
    }
  }

  private resetSelection(): void {
    // console.log('selection-reset: 0:0');
    this.list.selection = {
      'selection': {
        'start': 0, 'end': 0, 'len': 0
      },
    };
  }

  public focusSource(): void {
    if (this.list.selected >= 0) {
      // console.log('focusing-source');
      this.list.type = 'source';
      this.items._results[this.list.selected].nativeElement.children[1].children[1].focus();
    }
  }

  public focusTarget(): void {
    // console.log('focusing-target');
    this.list.type = 'target';
    this.items._results[this.list.selected].nativeElement.children[2].children[1].focus();
    this.resetSelection();
  }

  private focusNext(type: string): void {
    if ((this.list.selected + 1) < this.list.lines.length) {
      // console.log('focusing-next:', type);
      this.scrollTo(this.items._results[this.list.selected].nativeElement.offsetTop + this.items._results[this.list.selected].nativeElement.clientHeight + 20);
      this.list.extra[this.list.selected].selected = false;
      this.list.selected++;
      this.list.extra[this.list.selected].selected = true;
      this.list.type = type;
      this.items._results[this.list.selected].nativeElement.children[(type === 'target') ? (2) : (1)].children[1].focus();
    }
  }



  /** ... */

  public selectAllLines(): void {
    // console.log('selecting-all');
    this.list.selected = -2;
    this.list.type = 'target';
    this.resetSelection();
    this.selectAll();
  }

  public deselectAllLines(): void {
    // console.log('deselecting-all');
    this.list.selected = -1;
    this.list.type = '';
    this.resetSelection();
    this.deselectAll();
  }

  public isSelected(): boolean {
    return (this.list.selected !== -1);
  }

  public selectMultipleLines(index: number): void {
    // console.log('selecting-line', index);
    this.list.extra[index].selected = true;
    this.list.type = 'target';
    this.resetSelection();
    this.list.selected = (this.list.selected === -1) ? index : -2;
  }

  public deselectMultipleLines(index: number): void {
    // console.log('deselecting-line', index);
    this.list.extra[index].selected = false;
    this.list.type = 'target';
    this.resetSelection();

    if (this.list.selected === -2) {
      // check if there are other lines selected
      let countSelected = 0;
      const n = this.list.lines.length;

      for (let i = 0; i < n; i++) {
        if (this.list.extra[i].selected) {
          this.list.selected = i;
          countSelected++;

          if (countSelected === 2) {
            this.list.selected = -2;
            break;
          }
        }
      }
    }
    else {
      this.list.selected = -1;
      this.list.type = '';
    }
  }

  private selectAll(): void {
    // console.log('select-all');
    this.list.extra.forEach((line) => {
      line.selected = true;
    });
  }

  private deselectAll(): void {
    // console.log('deselect-all');
    this.list.extra.forEach((line) => {
      line.selected = false;
    });
  }

  public toggleSidebar(): void {
    this.app.config.sidebar = !this.app.config.sidebar;
  }

  public toggleAdvancedMode(): void {
    this.app.config.advanced = !this.app.config.advanced;
  }

  public selectionIsInvalid(): boolean {
    return (this.list.selection.end === 0 || this.list.selection.start === this.list.selection.len);
  }

  public saveData(): void {
    this.http.post('/save', JSON.stringify({ 'data': this.list.lines })).then(
      (res) => {
        // console.log('save: ' + res);
      },
      (err) => {
        // console.log('save error: ', err);
      }
    );
  }

  public removeSelected(): void {
    // console.log('removing...',);
    this.list.selected = -1;

    for (let i = 0; i < this.list.extra.length; i++) {
      if (this.list.extra[i].selected) {
        this.removeLine(i);
        i--;
      }
    }

    if (this.list.lines.length === 0) {
      this.addEmptyLine();
    }
  }

  private addEmptyLine(): void {
    this.list.lines.push({
      'extraBefore': '',
      'extraAfter': '',
      'source': '',
      'target': ''
    });
    this.list.extra.push({
      'selected': false,
      'updated': false,
      'loading': false
    });
  }

  private removeLine(i: number): void {
    this.list.lines.splice(i, 1);
    this.list.extra.splice(i, 1);
  }

  public canDelete(): boolean {
    return (this.list.selected !== -1);
  }

  public exitTranslating(): void {
    this.http.post('/finish', JSON.stringify({ 'data': this.list.lines })).then(
      (res) => {
        // console.log('error: ', res);
        window.location.href = '/';
      }, (err) => {
        // console.log('error: ', err);
        window.location.href = '/';
      }
    );
  }

  public scrollTo(position: number): void {
    let now = this.translate.nativeElement.scrollTop;

    if (position < now) {
      // console.log('scroll up from', now, 'to', position);
      const interval = setInterval(() => {
        const diff = (now - position) / 2;
        now -= (diff > 1 ? diff : 1);

        if (position < now) {
          this.translate.nativeElement.scrollTop = now;
        }
        else {
          this.translate.nativeElement.scrollTop = position;
          clearInterval(interval);
        }
      }, 20);
    }
    else if (position > now) {
      // console.log('scroll down from', now, 'to', position);
      const interval = setInterval(() => {
        const diff = (position - now) / 2;
        now += (diff > 1 ? diff : 1);

        if (position > now) {
          this.translate.nativeElement.scrollTop = now;
        }
        else {
          this.translate.nativeElement.scrollTop = position;
          clearInterval(interval);
        }
      }, 20);
    }
  }

  public canAutoBreak(): boolean {
    return (this.list.selected !== -1);
  }

  public autoBreak(): void {
    // console.log('autobreaking...');
    this.list.autobreaking = true;

    for (let i = 0; i < this.list.lines.length; i++) {
      if (this.list.extra[i].selected) {
        this.list.selected = i;
        this.regExSelected();
      }
    }

    // console.log(this.list.lines);
    this.list.autobreaking = false;

    setTimeout(() => {
      for (let i = 0; i < this.list.lines.length; i++) {
        this.items._results[i].nativeElement.children[1].children[1].value = this.list.lines[i].source;
        this.items._results[i].nativeElement.children[2].children[1].value = this.list.lines[i].target;
      }
    }, 500);
  }

  private regExSelected(): void {
    const extraBefore = this.list.lines[this.list.selected].extraBefore;
    const extraAfter = this.list.lines[this.list.selected].extraAfter;
    const str = this.list.lines[this.list.selected].source;
    const n = str.length;
    const rgx = str.search(/(\. [A-Z])|(\.\n+)|(\n+)|(\. [ÁÄČĎÉÍĹĽŇÓÔŔŠŤÚÝŽĚŘŮĂÂÎȘȚŞŢÖŐÜŰ])/);

    if (rgx !== -1) {
      if (str[rgx] === '.') {
        const before = str.slice(0, rgx);
        const selection = str.slice(rgx, rgx + 2);
        const after = str.slice(rgx + 2, n);
        this.breakWord(extraBefore, before, selection, after, extraAfter, false);
        this.list.extra[this.list.selected + 1].selected = true;
      }
      else {
        const str2 = str.slice(rgx + 1, n);
        const rgx2 = str2.search(/[^\s]/);

        if (rgx2 !== -1) {
          const before = str.slice(0, rgx);
          const selection = str.slice(rgx, rgx + rgx2 + 1);
          const after = str.slice(rgx + rgx2 + 1, n);
          this.breakWord(extraBefore, before, selection, after, extraAfter, false);
          this.list.extra[this.list.selected + 1].selected = true;
        }
        else {
          const before = str.slice(0, rgx);
          const selection = str.slice(rgx, n);
          const after = '';
          this.breakWord(extraBefore, before, selection, after, extraAfter, false);
          this.list.extra[this.list.selected + 1].selected = true;
        }
      }
    }

  }

  private noop(): void
  {}
}
