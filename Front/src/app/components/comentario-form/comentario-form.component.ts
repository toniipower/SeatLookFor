import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ComentarioService } from '../../services/comentario.service';

@Component({
  selector: 'app-comentario-form',
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  template: `
    <div class="formulario-comentario" *ngIf="asientoSeleccionado">
      <h3>Añadir comentario para el asiento {{asientoSeleccionado.zona}} - {{asientoSeleccionado.ejeX}}-{{asientoSeleccionado.ejeY}}</h3>
      <form [formGroup]="comentarioForm" (ngSubmit)="onSubmit()">
        <div class="form-group">
          <label for="opinion">Opinión:</label>
          <textarea id="opinion" formControlName="opinion" rows="4"></textarea>
          <div class="error" *ngIf="comentarioForm.get('opinion')?.errors?.['required'] && comentarioForm.get('opinion')?.touched">
            La opinión es requerida
          </div>
        </div>

        <div class="form-group">
          <label for="valoracion">Valoración:</label>
          <div class="estrellas">
            <span *ngFor="let estrella of [1,2,3,4,5]" 
                  (click)="setValoracion(estrella)"
                  [class.activa]="estrella <= comentarioForm.get('valoracion')?.value">
              ⭐
            </span>
          </div>
          <div class="error" *ngIf="comentarioForm.get('valoracion')?.errors?.['required'] && comentarioForm.get('valoracion')?.touched">
            La valoración es requerida
          </div>
        </div>

        <div class="form-group">
          <label for="foto">Foto del asiento:</label>
          <input type="file" id="foto" (change)="onFileSelected($event)" accept="image/*">
          <div class="preview" *ngIf="previewUrl">
            <img [src]="previewUrl" alt="Vista previa">
          </div>
        </div>

        <button type="submit" [disabled]="!comentarioForm.valid || !selectedFile">
          Enviar comentario
        </button>
      </form>
    </div>
  `,
  styles: [`
    .formulario-comentario {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-top: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .estrellas {
      display: flex;
      gap: 5px;
      font-size: 24px;
      cursor: pointer;
    }

    .estrellas span {
      opacity: 0.3;
      transition: opacity 0.3s;
    }

    .estrellas span.activa {
      opacity: 1;
    }

    .preview {
      margin-top: 10px;
    }

    .preview img {
      max-width: 200px;
      max-height: 150px;
      border-radius: 4px;
    }

    button {
      background-color: var(--primary-color);
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }

    .error {
      color: red;
      font-size: 12px;
      margin-top: 5px;
    }
  `]
})
export class ComentarioFormComponent {
  @Input() asientoSeleccionado: any;
  @Output() comentarioCreado = new EventEmitter<void>();

  comentarioForm: FormGroup;
  selectedFile: File | null = null;
  previewUrl: string | null = null;

  constructor(
    private fb: FormBuilder,
    private comentarioService: ComentarioService
  ) {
    this.comentarioForm = this.fb.group({
      opinion: ['', Validators.required],
      valoracion: [0, [Validators.required, Validators.min(1), Validators.max(5)]]
    });
  }

  setValoracion(valor: number) {
    this.comentarioForm.patchValue({ valoracion: valor });
  }

  onFileSelected(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
      this.selectedFile = file;
      // Crear preview
      const reader = new FileReader();
      reader.onload = () => {
        this.previewUrl = reader.result as string;
      };
      reader.readAsDataURL(file);
    }
  }

  onSubmit() {
    if (this.comentarioForm.valid && this.selectedFile) {
      const formData = new FormData();
      formData.append('opinion', this.comentarioForm.get('opinion')?.value);
      formData.append('valoracion', this.comentarioForm.get('valoracion')?.value);
      formData.append('foto', this.selectedFile);
      formData.append('idAsi', this.asientoSeleccionado.idAsi);

      this.comentarioService.crearComentario(formData).subscribe({
        next: () => {
          this.comentarioCreado.emit();
          this.comentarioForm.reset();
          this.selectedFile = null;
          this.previewUrl = null;
        },
        error: (error) => {
          console.error('Error al crear el comentario:', error);
        }
      });
    }
  }
} 